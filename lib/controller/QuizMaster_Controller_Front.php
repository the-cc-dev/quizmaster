<?php

class QuizMaster_Controller_Front
{

    /**
     * @var QuizMaster_Model_GlobalSettings
     */
    private $_settings = null;

    public function __construct()
    {
        $this->loadSettings();

        add_action('wp_enqueue_scripts', array($this, 'loadDefaultScripts'));
        add_shortcode('QuizMaster', array($this, 'shortcode'));
        add_shortcode('QuizMastertoplist', array($this, 'shortcodeToplist'));
    }

    public function loadDefaultScripts()
    {
        wp_enqueue_script('jquery');

        $data = array(
            'src' => plugins_url('css/quizMaster_front' . (QUIZMASTER_DEV ? '' : '.min') . '.css', QUIZMASTER_FILE),
            'deps' => array(),
            'ver' => QUIZMASTER_VERSION,
        );

        $data = apply_filters('quizMaster_front_style', $data);

        wp_enqueue_style('quizMaster_front_style', $data['src'], $data['deps'], $data['ver']);

        if ($this->_settings->isJsLoadInHead()) {
            $this->loadJsScripts(false, true, true);
        }
    }

    private function loadJsScripts($footer = true, $quiz = true, $toplist = false)
    {
        if ($quiz) {
            wp_enqueue_script(
                'quizMaster_front_javascript',
                plugins_url('js/quizMaster_front' . (QUIZMASTER_DEV ? '' : '.min') . '.js', QUIZMASTER_FILE),
                array('jquery-ui-sortable'),
                QUIZMASTER_VERSION,
                $footer
            );

            wp_localize_script('quizMaster_front_javascript', 'QuizMasterGlobal', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'loadData' => __('Loading', 'quizmaster'),
                'questionNotSolved' => __('You must answer this question.', 'quizmaster'),
                'questionsNotSolved' => __('You must answer all questions before you can completed the quiz.',
                    'quizmaster'),
                'fieldsNotFilled' => __('All fields have to be filled.', 'quizmaster')
            ));
        }

        if ($toplist) {
            wp_enqueue_script(
                'quizMaster_front_javascript_toplist',
                plugins_url('js/quizMaster_toplist' . (QUIZMASTER_DEV ? '' : '.min') . '.js', QUIZMASTER_FILE),
                array('jquery-ui-sortable'),
                QUIZMASTER_VERSION,
                $footer
            );

            if (!wp_script_is('quizMaster_front_javascript')) {
                wp_localize_script('quizMaster_front_javascript_toplist', 'QuizMasterGlobal', array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'loadData' => __('Loading', 'quizmaster'),
                    'questionNotSolved' => __('You must answer this question.', 'quizmaster'),
                    'questionsNotSolved' => __('You must answer all questions before you can completed the quiz.',
                        'quizmaster'),
                    'fieldsNotFilled' => __('All fields have to be filled.', 'quizmaster')
                ));
            }
        }

        if (!$this->_settings->isTouchLibraryDeactivate()) {
            wp_enqueue_script(
                'jquery-ui-touch-punch',
                plugins_url('js/jquery.ui.touch-punch.min.js', QUIZMASTER_FILE),
                array('jquery-ui-sortable'),
                '0.2.2',
                $footer
            );
        }
    }

    public function shortcode($attr)
    {
        $id = $attr[0];
        $content = '';

        if (!$this->_settings->isJsLoadInHead()) {
            $this->loadJsScripts();
        }

        if (is_numeric($id)) {
            ob_start();

            $this->handleShortCode($id);

            $content = ob_get_contents();

            ob_end_clean();
        }

        if ($this->_settings->isAddRawShortcode()) {
            return '[raw]' . $content . '[/raw]';
        }

        return $content;
    }

    public function handleShortCode($id)
    {
        $view = new QuizMaster_View_FrontQuiz();
        $view = apply_filters( 'quizmaster_view_load', $view, 'FrontQuiz' );
        $view = apply_filters( 'quizmaster_view_load_front_quiz', $view );

        $quizMapper = new QuizMaster_Model_QuizMapper();
        $questionMapper = new QuizMaster_Model_QuestionMapper();
        $categoryMapper = new QuizMaster_Model_CategoryMapper();
        $formMapper = new QuizMaster_Model_FormMapper();

        $quiz = $quizMapper->fetch($id);

        $maxQuestion = false;

        if ($quiz->isShowMaxQuestion() && $quiz->getShowMaxQuestionValue() > 0) {

            $value = $quiz->getShowMaxQuestionValue();

            if ($quiz->isShowMaxQuestionPercent()) {
                $count = $questionMapper->count($id);

                $value = ceil($count * $value / 100);
            }

            $question = $questionMapper->fetchAll($id, true, $value);
            $maxQuestion = true;

        } else {
            $question = $questionMapper->fetchAll($id);
        }

        if (empty($quiz) || empty($question)) {
            echo '';

            return;
        }

        $view->quiz = $quiz;
        $view->question = $question;
        $view->category = $categoryMapper->fetchByQuiz($quiz->getId());
        $view->forms = $formMapper->fetch($quiz->getId());

        $view = apply_filters( 'quizmaster_view_before_render', $view );
        if ($maxQuestion) {
            $view->showMaxQuestion();
        } else {
            $view->show();
        }
    }

    public function shortcodeToplist($attr)
    {
        $id = $attr[0];
        $content = '';

        if (!$this->_settings->isJsLoadInHead()) {
            $this->loadJsScripts(true, false, true);
        }

        if (is_numeric($id)) {
            ob_start();

            $this->handleShortCodeToplist($id, isset($attr['q']));

            $content = ob_get_contents();

            ob_end_clean();
        }

        if ($this->_settings->isAddRawShortcode() && !isset($attr['q'])) {
            return '[raw]' . $content . '[/raw]';
        }

        return $content;
    }

    private function handleShortCodeToplist($quizId, $inQuiz = false)
    {
        $quizMapper = new QuizMaster_Model_QuizMapper();
        $view = new QuizMaster_View_FrontToplist();

        $quiz = $quizMapper->fetch($quizId);

        if ($quiz->getId() <= 0 || !$quiz->isToplistActivated()) {
            echo '';

            return;
        }

        $view->quiz = $quiz;
        $view->points = $quizMapper->sumQuestionPoints($quizId);
        $view->inQuiz = $inQuiz;
        $view->show();
    }

    private function loadSettings()
    {
        $mapper = new QuizMaster_Model_GlobalSettingsMapper();

        $this->_settings = $mapper->fetchAll();
    }

    public static function ajaxQuizLoadData($data)
    {
        $id = $data['quizId'];

        $view = new QuizMaster_View_FrontQuiz();

        $quizMapper = new QuizMaster_Model_QuizMapper();
        $questionMapper = new QuizMaster_Model_QuestionMapper();
        $categoryMapper = new QuizMaster_Model_CategoryMapper();
        $formMapper = new QuizMaster_Model_FormMapper();

        $quiz = $quizMapper->fetch($id);

        if ($quiz->isShowMaxQuestion() && $quiz->getShowMaxQuestionValue() > 0) {

            $value = $quiz->getShowMaxQuestionValue();

            if ($quiz->isShowMaxQuestionPercent()) {
                $count = $questionMapper->count($id);

                $value = ceil($count * $value / 100);
            }

            $question = $questionMapper->fetchAll($id, true, $value);

        } else {
            $question = $questionMapper->fetchAll($id);
        }

        if (empty($quiz) || empty($question)) {
            return null;
        }

        $view->quiz = $quiz;
        $view->question = $question;
        $view->category = $categoryMapper->fetchByQuiz($quiz->getId());
        $view->forms = $formMapper->fetch($quiz->getId());

        return json_encode($view->getQuizData());
    }
}