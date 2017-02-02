<?php

class QuizMaster_Controller_Email {

  private $email = false;

  const QUIZMASTER_EMAIL_TRIGGER_FIELD = 'qm_email_trigger';
  const QUIZMASTER_EMAIL_ENABLED_FIELD = 'qm_email_enabled';

  public function __construct() {
    $this->addEmailTriggers();
  }

  public function addEmailTriggers() {
    add_action('quizmaster_completed_quiz', array( $this, 'sendEmailCompletedQuiz' ));
  }

  public function send() {
    wp_mail(
      $this->email->getRecipients(),
      $this->email->getSubject(),
      $this->email->getMessage(),
      $this->email->getHeaders()
    );
  }

  public function setMessage() {
    $msg = quizmaster_parse_template('emails/student_completion.php');
    $this->email->setMessage( $msg );
  }

  public function sendEmailCompletedQuiz() {

    $trigger = 'completed_quiz';

    define( QUIZMASTER_EMAIL_TRIGGER_FIELD, 'qm_email_trigger');
    define( QUIZMASTER_EMAIL_ENABLED_FIELD, 'qm_email_enabled');

    $posts = get_posts(array(
    	'numberposts'	=> -1,
    	'post_type'		=> 'quizmaster_email',
    	'meta_query'	=> array(
    		'relation'		=> 'AND',
    		array(
    			'key'	 	    => QUIZMASTER_EMAIL_TRIGGER_FIELD,
    			'value'	  	=> $trigger,
    			'compare' 	=> '=',
    		),
    		array(
    			'key'	  	  => QUIZMASTER_EMAIL_ENABLED_FIELD,
    			'value'	  	=> '1',
    			'compare' 	=> '=',
    		),
    	),
    ));

    foreach( $posts as $emailPost ) {
      $this->email = new QuizMaster_Model_Email( $emailPost->ID );
      $this->setMessage();
      $this->send();
    }

  }

}
