<?php

class QuizMaster_Controller_Email {

   const QUIZMASTER_EMAIL_TRIGGER_FIELD = 'qm_email_trigger';
   const QUIZMASTER_EMAIL_ENABLED_FIELD = 'qm_email_enabled';

  public function __constructor() {
    $this->addEmailTriggers();
  }

  public function addEmailTriggers() {

    add_action('quizmaster_completed_quiz', 'sendEmailCompletedQuiz' );

  }

  public function send() {

  }

}
