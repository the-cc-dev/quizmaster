<?php

class QuizMaster_Controller_InfoAdaptation extends QuizMaster_Controller_Controller
{

    public function route()
    {
        $this->showAction();
    }

    private function showAction()
    {
        $view = new QuizMaster_View_InfoAdaptation();

        $view->show();
    }
}