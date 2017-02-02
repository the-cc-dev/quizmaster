<?php

class QuizMaster_Controller_Quiz extends QuizMaster_Controller_Controller
{
    public function route()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : 'show';

        switch ($action) {
            case 'show':
                $this->showAction();
                break;
            case 'addEdit':
                $this->addEditQuiz();
                break;
            case 'delete':
                if (isset($_GET['id'])) {
                    $this->deleteAction($_GET['id']);
                }
                break;
            case 'deleteMulti':
                $this->deleteMultiAction();
                break;
            default:
                $this->showAction();
                break;
        }
    }

    public function routeAction()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : 'show';

        switch ($action) {
            default:
                $this->showActionHook();
                break;
        }
    }

    private function showActionHook()
    {
        if (!empty($_REQUEST['_wp_http_referer'])) {
            wp_redirect(remove_query_arg(array('_wp_http_referer', '_wpnonce'), wp_unslash($_SERVER['REQUEST_URI'])));
            exit;
        }

        if (!class_exists('WP_List_Table')) {
            require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
        }

        add_filter('manage_' . get_current_screen()->id . '_columns',
            array('QuizMaster_View_QuizOverallTable', 'getColumnDefs'));

        add_screen_option('per_page', array(
            'label' => __('Quiz', 'quizmaster'),
            'default' => 20,
            'option' => 'quizmaster_quiz_overview_per_page'
        ));
    }

    public function isLockQuiz()
    {
        $quizId = (int)$this->_post['quizId'];
        $userId = get_current_user_id();
        $data = array();

        $lockMapper = new QuizMaster_Model_LockMapper();
        $quizMapper = new QuizMaster_Model_QuizMapper();
        $prerequisiteMapper = new QuizMaster_Model_PrerequisiteMapper();

        $quiz = $quizMapper->fetch($this->_post['quizId']);

        if ($quiz === null || $quiz->getId() <= 0) {
            return null;
        }

        if ($this->isPreLockQuiz($quiz)) {
            $lockIp = $lockMapper->isLock($this->_post['quizId'], $this->getIp(), $userId,
                QuizMaster_Model_Lock::TYPE_QUIZ);
            $lockCookie = false;
            $cookieTime = $quiz->getQuizRunOnceTime();

            if (isset($this->_cookie['quizMaster_lock']) && $userId == 0 && $quiz->isQuizRunOnceCookie()) {
                $cookieJson = json_decode($this->_cookie['quizMaster_lock'], true);

                if ($cookieJson !== false) {
                    if (isset($cookieJson[$this->_post['quizId']]) && $cookieJson[$this->_post['quizId']] == $cookieTime) {
                        $lockCookie = true;
                    }
                }
            }

            $data['lock'] = array(
                'is' => ($lockIp || $lockCookie),
                'pre' => true
            );
        }

        if ($quiz->isPrerequisite()) {
            $quizIds = array();

            if ($userId > 0) {
                $quizIds = $prerequisiteMapper->getNoPrerequisite($quizId, $userId);
            } else {
                $checkIds = $prerequisiteMapper->fetchQuizIds($quizId);

                if (isset($this->_cookie['quizMaster_result'])) {
                    $r = json_decode($this->_cookie['quizMaster_result'], true);

                    if ($r !== null && is_array($r)) {
                        foreach ($checkIds as $id) {
                            if (!isset($r[$id]) || !$r[$id]) {
                                $quizIds[] = $id;
                            }
                        }
                    }
                } else {
                    $quizIds = $checkIds;
                }
            }

            if (!empty($quizIds)) {
                $names = $quizMapper->fetchCol($quizIds, 'name');

                if (!empty($names)) {
                    $data['prerequisite'] = implode(', ', $names);
                }
            }

        }

        if ($quiz->isStartOnlyRegisteredUser()) {
            $data['startUserLock'] = (int)!is_user_logged_in();
        }

        return $data;
    }

    private function getCurrentPage()
    {
        $pagenum = isset($_REQUEST['paged']) ? absint($_REQUEST['paged']) : 0;

        return max(1, $pagenum);
    }

    private function showAction()
    {
        if (!current_user_can('quizMaster_show')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $view = new QuizMaster_View_QuizOverall();

        $m = new QuizMaster_Model_QuizMapper();
        $categoryMapper = new QuizMaster_Model_CategoryMapper();

        $per_page = (int)get_user_option('quizmaster_quiz_overview_per_page');
        if (empty($per_page) || $per_page < 1) {
            $per_page = 20;
        }

        $current_page = $this->getCurrentPage();
        $search = isset($_GET['s']) ? trim($_GET['s']) : '';
        $orderBy = isset($_GET['orderby']) ? trim($_GET['orderby']) : '';
        $order = isset($_GET['order']) ? trim($_GET['order']) : '';
        $offset = ($current_page - 1) * $per_page;
        $limit = $per_page;
        $filter = array();

        if (isset($_GET['cat'])) {
            $filter['cat'] = $_GET['cat'];
        }

        $result = $m->fetchTable($orderBy, $order, $search, $limit, $offset, $filter);

        $view->quizItems = $result['quiz'];
        $view->quizCount = $result['count'];
        $view->categoryItems = $categoryMapper->fetchAll(QuizMaster_Model_Category::CATEGORY_TYPE_QUIZ);;
        $view->perPage = $per_page;

        $view->show();
    }

    private function formHandler($quizId, $post)
    {
        if (!isset($post['form'])) {
            return false;
        }

        $form = $post['form'];

        unset($form[0]);

        if (empty($form)) {
            return false;
        }

        $formMapper = new QuizMaster_Model_FormMapper();

        $deleteIds = array();
        $forms = array();
        $sort = 0;

        foreach ($form as $f) {
            $f['fieldname'] = trim($f['fieldname']);

            if (empty($f['fieldname'])) {
                continue;
            }

            if ((int)$f['form_id'] && (int)$f['form_delete']) {
                $deleteIds[] = (int)$f['form_id'];
                continue;
            }

            $f['sort'] = $sort++;
            $f['quizId'] = $quizId;

            if ($f['type'] == QuizMaster_Model_Form::FORM_TYPE_SELECT || $f['type'] == QuizMaster_Model_Form::FORM_TYPE_RADIO) {
                if (!empty($f['data'])) {
                    $items = explode("\n", $f['data']);
                    $f['data'] = array();

                    foreach ($items as $item) {
                        $item = trim($item);

                        if (!empty($item)) {
                            $f['data'][] = $item;
                        }
                    }
                }
            }

            if (empty($f['data']) || !is_array($f['data'])) {
                $f['data'] = null;
            }

            $forms[] = new QuizMaster_Model_Form($f);
        }

        if (!empty($deleteIds)) {
            $formMapper->deleteForm($deleteIds, $quizId);
        }

        $formMapper->update($forms);

        return !empty($forms);
    }

    private function deleteAction($id)
    {
        if (!current_user_can('quizMaster_delete_quiz')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $m = new QuizMaster_Model_QuizMapper();

        $m->deleteAll($id);

        QuizMaster_View_View::admin_notices(__('Quiz deleted', 'quizmaster'), 'info');

        $this->showAction();
    }

    private function deleteMultiAction()
    {
        if (!current_user_can('quizMaster_delete_quiz')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $m = new QuizMaster_Model_QuizMapper();

        if (!empty($_POST['ids'])) {
            foreach ($_POST['ids'] as $id) {
                $m->deleteAll($id);
            }
        }

        QuizMaster_View_View::admin_notices(__('Quiz deleted', 'quizmaster'), 'info');

        $this->showAction();
    }

    private function checkValidit($post)
    {
        return (isset($post['name']) && !empty($post['name']) && isset($post['text']) && !empty($post['text']));
    }

    private function filterResultTextGrade($post)
    {
        $activ = array_keys($post['resultTextGrade']['activ'], '1');
        $result = array();

        foreach ($activ as $k) {
            $result['text'][] = $post['resultTextGrade']['text'][$k];
            $result['prozent'][] = (float)str_replace(',', '.', $post['resultTextGrade']['prozent'][$k]);
        }

        return $result;
    }

    private function setResultCookie(QuizMaster_Model_Quiz $quiz)
    {
        $prerequisite = new QuizMaster_Model_PrerequisiteMapper();

        if (get_current_user_id() == 0 && $prerequisite->isQuizId($quiz->getId())) {
            $cookieData = array();

            if (isset($this->_cookie['quizMaster_result'])) {
                $d = json_decode($this->_cookie['quizMaster_result'], true);

                if ($d !== null && is_array($d)) {
                    $cookieData = $d;
                }
            }

            $cookieData[$quiz->getId()] = 1;

            $url = parse_url(get_bloginfo('url'));

            setcookie('quizMaster_result', json_encode($cookieData), time() + 60 * 60 * 24 * 300,
                empty($url['path']) ? '/' : $url['path']);
        }
    }

    public function isPreLockQuiz(QuizMaster_Model_Quiz $quiz)
    {
        $userId = get_current_user_id();

        if ($quiz->isQuizRunOnce()) {
            switch ($quiz->getQuizRunOnceType()) {
                case QuizMaster_Model_Quiz::QUIZ_RUN_ONCE_TYPE_ALL:
                    return true;
                case QuizMaster_Model_Quiz::QUIZ_RUN_ONCE_TYPE_ONLY_USER:
                    return $userId > 0;
                case QuizMaster_Model_Quiz::QUIZ_RUN_ONCE_TYPE_ONLY_ANONYM:
                    return $userId == 0;
            }
        }

        return false;
    }

    private function getIp()
    {
        if (get_current_user_id() > 0) {
            return '0';
        } else {
            return filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
        }
    }

    public function htmlEmailContent()
    {
        return 'text/html';
    }

    private function setCategoryOverview($catArray, $categories)
    {
        $cats = array();

        foreach ($categories as $cat) {
            /* @var $cat QuizMaster_Model_Category */

            if (!$cat->getCategoryId()) {
                $cat->setCategoryName(__('Not categorized', 'quizmaster'));
            }

            $cats[$cat->getCategoryId()] = $cat->getCategoryName();
        }

        $a = __('Categories', 'quizmaster') . ":\n";

        foreach ($catArray as $id => $value) {
            if (!isset($cats[$id])) {
                continue;
            }

            $a .= '* ' . str_pad($cats[$id], 35, '.') . ((float)$value) . "%\n";
        }

        return $a;
    }

    public static function ajaxSetQuizMultipleCategories($data)
    {
        if (!current_user_can('quizMaster_edit_quiz')) {
            return json_encode(array());
        }

        $quizMapper = new QuizMaster_Model_QuizMapper();

        $quizMapper->setMultipeCategories($data['quizIds'], $data['categoryId']);

        return json_encode(array());
    }

    public static function ajaxLoadQuizData($data)
    {
        $quizId = (int)$data['quizId'];

        $quizMapper = new QuizMaster_Model_QuizMapper();
        $toplistController = new QuizMaster_Controller_Toplist();
        $statisticController = new QuizMaster_Controller_Statistics();

        $quiz = $quizMapper->fetch($quizId);
        $data = array();

        if ($quiz === null || $quiz->getId() <= 0) {
            return json_encode(array());
        }

        $data['toplist'] = $toplistController->getAddToplist($quiz);
        $data['averageResult'] = $statisticController->getAverageResult($quizId);

        return json_encode($data);
    }

    public static function ajaxQuizCheckLock()
    {
        // workaround ...
        $_POST = $_POST['data'];

        $quizController = new QuizMaster_Controller_Quiz();

        return json_encode($quizController->isLockQuiz());
    }

    public static function ajaxResetLock($data)
    {
        if (!current_user_can('quizMaster_edit_quiz')) {
            return json_encode(array());
        }

        $quizId = (int)$data['quizId'];

        $lm = new QuizMaster_Model_LockMapper();
        $qm = new QuizMaster_Model_QuizMapper();

        $q = $qm->fetch($quizId);

        if ($q->getId() > 0) {
            $q->setQuizRunOnceTime(time());

            $qm->save($q);

            $lm->deleteByQuizId($quizId, QuizMaster_Model_Lock::TYPE_QUIZ);
        }

        return json_encode(array());
    }

    public static function ajaxCompletedQuiz($data)
    {
        // workaround ...
        $_POST = $_POST['data'];

        $ctr = new QuizMaster_Controller_Quiz();

        $lockMapper = new QuizMaster_Model_LockMapper();
        $quizMapper = new QuizMaster_Model_QuizMapper();
        $formMapper = new QuizMaster_Model_FormMapper();

        $is100P = $data['results']['comp']['result'] == 100;

        $quiz = $quizMapper->fetch($data['quizId']);

        if ($quiz === null || $quiz->getId() <= 0) {
          return json_encode(array());
        }

        $forms = $formMapper->fetch($quiz->getId());

        $ctr->setResultCookie($quiz);

        if (!$ctr->isPreLockQuiz($quiz)) {

          $statistics = new QuizMaster_Controller_Statistics();
          $statistics->save($quiz);
          do_action('quizmaster_completed_quiz');

          if ($is100P) {
            do_action('quizmaster_completed_quiz_100_percent');
          }

          return json_encode(array());
        }

        $lockMapper->deleteOldLock(60 * 60 * 24 * 7, $data['quizId'], time(), QuizMaster_Model_Lock::TYPE_QUIZ,
            0);

        $lockIp = $lockMapper->isLock($data['quizId'], $ctr->getIp(), get_current_user_id(),
            QuizMaster_Model_Lock::TYPE_QUIZ);
        $lockCookie = false;
        $cookieTime = $quiz->getQuizRunOnceTime();
        $cookieJson = null;

        if (isset($ctr->_cookie['quizMaster_lock']) && get_current_user_id() == 0 && $quiz->isQuizRunOnceCookie()) {
          $cookieJson = json_decode($ctr->_cookie['quizMaster_lock'], true);

          if ($cookieJson !== false) {
            if (isset($cookieJson[$data['quizId']]) && $cookieJson[$data['quizId']] == $cookieTime) {
              $lockCookie = true;
            }
          }
        }

        if (!$lockIp && !$lockCookie) {

          $statistics = new QuizMaster_Controller_Statistics();
          $statistics->save($quiz);

          do_action('quizmaster_completed_quiz');

          if ($is100P) {
              do_action('quizmaster_completed_quiz_100_percent');
          }

          if (get_current_user_id() == 0 && $quiz->isQuizRunOnceCookie()) {
              $cookieData = array();

              if ($cookieJson !== null || $cookieJson !== false) {
                  $cookieData = $cookieJson;
              }

              $cookieData[$data['quizId']] = $quiz->getQuizRunOnceTime();
              $url = parse_url(get_bloginfo('url'));

              setcookie('quizMaster_lock', json_encode($cookieData), time() + 60 * 60 * 24 * 60,
                  empty($url['path']) ? '/' : $url['path']);
          }

          $lock = new QuizMaster_Model_Lock();

          $lock->setUserId(get_current_user_id());
          $lock->setQuizId($data['quizId']);
          $lock->setLockDate(time());
          $lock->setLockIp($ctr->getIp());
          $lock->setLockType(QuizMaster_Model_Lock::TYPE_QUIZ);

          $lockMapper->insert($lock);
        }

        return json_encode(array());
    }
}
