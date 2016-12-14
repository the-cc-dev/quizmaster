<?php

/**
 * @since 0.23
 */
class QuizMaster_Controller_Ajax
{

    private $_adminCallbacks = array();
    private $_frontCallbacks = array();

    public function init()
    {
        $this->initCallbacks();

        add_action('wp_ajax_wp_pro_quiz_admin_ajax', array($this, 'adminAjaxCallback'));
        add_action('wp_ajax_nopriv_wp_pro_quiz_admin_ajax', array($this, 'frontAjaxCallback'));
    }

    public function adminAjaxCallback()
    {
        $this->ajaxCallbackHandler(true);
    }

    public function frontAjaxCallback()
    {
        $this->ajaxCallbackHandler(false);
    }

    private function ajaxCallbackHandler($admin)
    {
        $func = isset($_POST['func']) ? $_POST['func'] : '';
        $data = isset($_POST['data']) ? $_POST['data'] : null;
        $calls = $admin ? $this->_adminCallbacks : $this->_frontCallbacks;

        if (isset($calls[$func])) {
            $r = call_user_func($calls[$func], $data, $func);

            if ($r !== null) {
                echo $r;
            }
        }

        exit;
    }

    private function initCallbacks()
    {
        $this->_adminCallbacks = array(
            'categoryAdd' => array('QuizMaster_Controller_Category', 'ajaxAddCategory'),
            'categoryDelete' => array('QuizMaster_Controller_Category', 'ajaxDeleteCategory'),
            'categoryEdit' => array('QuizMaster_Controller_Category', 'ajaxEditCategory'),
            'statisticLoadHistory' => array('QuizMaster_Controller_Statistics', 'ajaxLoadHistory'),
            'statisticLoadUser' => array('QuizMaster_Controller_Statistics', 'ajaxLoadStatisticUser'),
            'statisticResetNew' => array('QuizMaster_Controller_Statistics', 'ajaxRestStatistic'),
            'statisticLoadOverviewNew' => array('QuizMaster_Controller_Statistics', 'ajaxLoadStatsticOverviewNew'),
            'templateEdit' => array('QuizMaster_Controller_Template', 'ajaxEditTemplate'),
            'templateDelete' => array('QuizMaster_Controller_Template', 'ajaxDeleteTemplate'),
            'quizLoadData' => array('QuizMaster_Controller_Front', 'ajaxQuizLoadData'),
            'setQuizMultipleCategories' => array('QuizMaster_Controller_Quiz', 'ajaxSetQuizMultipleCategories'),
            'setQuestionMultipleCategories' => array(
                'QuizMaster_Controller_Question',
                'ajaxSetQuestionMultipleCategories'
            ),
            'loadQuestionsSort' => array('QuizMaster_Controller_Question', 'ajaxLoadQuestionsSort'),
            'questionSaveSort' => array('QuizMaster_Controller_Question', 'ajaxSaveSort'),
            'questionaLoadCopyQuestion' => array('QuizMaster_Controller_Question', 'ajaxLoadCopyQuestion'),
            'loadQuizData' => array('QuizMaster_Controller_Quiz', 'ajaxLoadQuizData'),
            'resetLock' => array('QuizMaster_Controller_Quiz', 'ajaxResetLock'),
            'adminToplist' => array('QuizMaster_Controller_Toplist', 'ajaxAdminToplist'),
            'completedQuiz' => array('QuizMaster_Controller_Quiz', 'ajaxCompletedQuiz'),
            'quizCheckLock' => array('QuizMaster_Controller_Quiz', 'ajaxQuizCheckLock'),
            'addInToplist' => array('QuizMaster_Controller_Toplist', 'ajaxAddInToplist'),
            'showFrontToplist' => array('QuizMaster_Controller_Toplist', 'ajaxShowFrontToplist')
        );

        //nopriv
        $this->_frontCallbacks = array(
            'quizLoadData' => array('QuizMaster_Controller_Front', 'ajaxQuizLoadData'),
            'loadQuizData' => array('QuizMaster_Controller_Quiz', 'ajaxLoadQuizData'),
            'completedQuiz' => array('QuizMaster_Controller_Quiz', 'ajaxCompletedQuiz'),
            'quizCheckLock' => array('QuizMaster_Controller_Quiz', 'ajaxQuizCheckLock'),
            'addInToplist' => array('QuizMaster_Controller_Toplist', 'ajaxAddInToplist'),
            'showFrontToplist' => array('QuizMaster_Controller_Toplist', 'ajaxShowFrontToplist')
        );
    }
}
