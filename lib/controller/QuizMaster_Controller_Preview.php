<?php

class QuizMaster_Controller_Preview extends QuizMaster_Controller_Controller
{

    public function route()
    {

        wp_enqueue_script(
            'quizMaster_front_javascript',
            plugins_url('js/quizMaster_front' . (QUIZMASTER_DEV ? '' : '.min') . '.js', QUIZMASTER_FILE),
            array('jquery', 'jquery-ui-sortable'),
            QUIZMASTER_VERSION
        );

        wp_localize_script('quizMaster_front_javascript', 'QuizMasterGlobal', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'loadData' => __('Loading', 'quizmaster'),
            'questionNotSolved' => __('You must answer this question.', 'quizmaster'),
            'questionsNotSolved' => __('You must answer all questions before you can completed the quiz.',
                'quizmaster'),
            'fieldsNotFilled' => __('All fields have to be filled.', 'quizmaster')
        ));

        wp_enqueue_style(
            'quizMaster_front_style',
            plugins_url('css/quizMaster_front' . (QUIZMASTER_DEV ? '' : '.min') . '.css', QUIZMASTER_FILE),
            array(),
            QUIZMASTER_VERSION
        );

        $this->showAction($_GET['id']);
    }

    public function showAction($id)
    {
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

        $view->quiz = $quiz;
        $view->question = $question;
        $view->category = $categoryMapper->fetchByQuiz($quiz->getId());
        $view->forms = $formMapper->fetch($quiz->getId());

        $view->show(true);
    }
}