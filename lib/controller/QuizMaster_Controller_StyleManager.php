<?php

class QuizMaster_Controller_StyleManager extends QuizMaster_Controller_Controller
{

    public function route()
    {
        $this->show();
    }

    private function show()
    {

        wp_enqueue_style(
            'quizMaster_front_style',
            plugins_url('css/quizMaster_front.min.css', QUIZMASTER_FILE),
            array(),
            QUIZMASTER_VERSION
        );

        $view = new QuizMaster_View_StyleManager();

        $view->show();
    }
}