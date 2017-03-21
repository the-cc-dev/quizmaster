<?php

class QuizMaster_Controller_Question extends QuizMaster_Controller_Controller
{
    private $_quizId;

    public function route()
    {
        if (!isset($_GET['quiz_id']) || empty($_GET['quiz_id'])) {
            QuizMaster_View_View::admin_notices(__('Quiz not found', 'quizmaster'), 'error');

            return;
        }

        $this->_quizId = (int)$_GET['quiz_id'];
        $action = isset($_GET['action']) ? $_GET['action'] : 'show';

        $m = new QuizMaster_Model_QuizMapper();

        if ($m->exists($this->_quizId) == 0) {
            QuizMaster_View_View::admin_notices(__('Quiz not found', 'quizmaster'), 'error');

            return;
        }

        switch ($action) {
            case 'show':
                $this->showAction();
                break;
            case 'addEdit':
                $this->addEditQuestion((int)$_GET['quiz_id']);
                break;
            case 'delete':
                $this->deleteAction($_GET['id']);
                break;
            case 'delete_multi':
                $this->deleteMultiAction();
                break;
            case 'save_sort':
                $this->saveSort();
                break;
            case 'load_question':
                $this->loadQuestion($_GET['quiz_id']);
                break;
            case 'copy_question':
                $this->copyQuestion($_GET['quiz_id']);
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
            array('QuizMaster_View_QuestionOverallTable', 'getColumnDefs'));

        add_screen_option('per_page', array(
            'label' => __('Questions', 'quizmaster'),
            'default' => 20,
            'option' => 'quizmaster_question_overview_per_page'
        ));
    }

    private function saveTemplate()
    {
        $questionModel = $this->getPostQuestionModel(0, 0);

        $templateMapper = new QuizMaster_Model_TemplateMapper();
        $template = new QuizMaster_Model_Template();

        if ($this->_post['templateSaveList'] == '0') {
            $template->setName(trim($this->_post['templateName']));
        } else {
            $template = $templateMapper->fetchById($this->_post['templateSaveList'], false);
        }

        $template->setType(QuizMaster_Model_Template::TEMPLATE_TYPE_QUESTION);

        $template->setData(array(
            'question' => $questionModel
        ));

        return $templateMapper->save($template);
    }

    public function copyQuestion($quizId)
    {

        if (!current_user_can('quizMaster_edit_quiz')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $m = new QuizMaster_Model_QuestionMapper();

        $questions = $m->fetchById($this->_post['copyIds']);

        foreach ($questions as $question) {
            $question->setId(0);
            $question->setQuizId($quizId);

            $m->save($question);
        }

        QuizMaster_View_View::admin_notices(__('questions copied', 'quizmaster'), 'info');

        $this->showAction();
    }

    public function loadQuestion($quizId)
    {

        if (!current_user_can('quizMaster_edit_quiz')) {
            echo json_encode(array());
            exit;
        }

        $quizMapper = new QuizMaster_Model_QuizMapper();
        $questionMapper = new QuizMaster_Model_QuestionMapper();
        $data = array();

        $quiz = $quizMapper->fetchAll();

        foreach ($quiz as $qz) {

            if ($qz->getId() == $quizId) {
                continue;
            }

            $question = $questionMapper->fetchAll($qz->getId());
            $questionArray = array();

            foreach ($question as $qu) {
                $questionArray[] = array(
                    'name' => $qu->getTitle(),
                    'id' => $qu->getId()
                );
            }

            $data[] = array(
                'name' => $qz->getName(),
                'id' => $qz->getId(),
                'question' => $questionArray
            );
        }

        echo json_encode($data);

        exit;
    }

    public function saveSort()
    {

        if (!current_user_can('quizMaster_edit_quiz')) {
            exit;
        }

        $mapper = new QuizMaster_Model_QuestionMapper();
        $map = $this->_post['sort'];

        foreach ($map as $k => $v) {
            $mapper->updateSort($v, $k);
        }

        exit;
    }

    public function deleteAction($id)
    {

        if (!current_user_can('quizMaster_delete_quiz')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $mapper = new QuizMaster_Model_QuestionMapper();
        $mapper->setOnlineOff($id);

        $this->showAction();
    }

    public function deleteMultiAction()
    {
        if (!current_user_can('quizMaster_delete_quiz')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $mapper = new QuizMaster_Model_QuestionMapper();

        if (!empty($_POST['ids'])) {
            foreach ($_POST['ids'] as $id) {
                $mapper->setOnlineOff($id);
            }
        }

        $this->showAction();
    }

    public function clear($a)
    {
        foreach ($a as $k => $v) {
            if (is_array($v)) {
                $a[$k] = $this->clear($a[$k]);
            }

            if (is_string($a[$k])) {
                $a[$k] = trim($a[$k]);

                if ($a[$k] != '') {
                    continue;
                }
            }

            if (empty($a[$k])) {
                unset($a[$k]);
            }
        }

        return $a;
    }

    private function getCurrentPage()
    {
        $pagenum = isset($_REQUEST['paged']) ? absint($_REQUEST['paged']) : 0;

        return max(1, $pagenum);
    }

    public static function ajaxSetQuestionMultipleCategories($data)
    {
        if (!current_user_can('quizMaster_edit_quiz')) {
            return json_encode(array());
        }

        $quizMapper = new QuizMaster_Model_QuestionMapper();

        $quizMapper->setMultipeCategories($data['questionIds'], $data['categoryId']);

        return json_encode(array());
    }

    public static function ajaxLoadQuestionsSort($data)
    {
        if (!current_user_can('quizMaster_edit_quiz')) {
            return json_encode(array());
        }

        $quizMapper = new QuizMaster_Model_QuestionMapper();

        $questions = $quizMapper->fetchAllList($data['quizId'], array('id', 'title'), true);

        return json_encode($questions);
    }

    public static function ajaxSaveSort($data)
    {
        if (!current_user_can('quizMaster_edit_quiz')) {
            return json_encode(array());
        }

        $mapper = new QuizMaster_Model_QuestionMapper();

        foreach ($data['sort'] as $k => $v) {
            $mapper->updateSort($v, $k);
        }

        return json_encode(array());
    }

    public static function ajaxLoadCopyQuestion($data)
    {
        if (!current_user_can('quizMaster_edit_quiz')) {
            echo json_encode(array());
            exit;
        }

        $quizId = $data['quizId'];
        $quizMapper = new QuizMaster_Model_QuizMapper();
        $questionMapper = new QuizMaster_Model_QuestionMapper();
        $data = array();

        $quiz = $quizMapper->fetchAll();

        foreach ($quiz as $qz) {

            if ($qz->getId() == $quizId) {
                continue;
            }

            $question = $questionMapper->fetchAll($qz->getId());
            $questionArray = array();

            foreach ($question as $qu) {
                $questionArray[] = array(
                    'name' => $qu->getTitle(),
                    'id' => $qu->getId()
                );
            }

            $data[] = array(
                'name' => $qz->getName(),
                'id' => $qz->getId(),
                'question' => $questionArray
            );
        }

        return json_encode($data);
    }
}
