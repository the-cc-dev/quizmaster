<?php

class QuizMaster_Controller_Email {

  private $email = false;
  private $quiz  = false;
  private $score  = false;

  const QUIZMASTER_EMAIL_TRIGGER_FIELD = 'qm_email_trigger';
  const QUIZMASTER_EMAIL_ENABLED_FIELD = 'qm_email_enabled';

  public function __construct() {
    $this->addEmailTriggers();
    $this->addShortcodes();
  }

  public function addShortcodes() {
    add_shortcode('quizdata', array($this, 'quizDataShortcode'));
    add_shortcode('quiztaker_email', array($this, 'quizTakerEmailShortcode'));
  }

  public function quizTakerEmailShortcode() {
    $user = wp_get_current_user();
    return $user->user_email;
  }

  public function quizDataShortcode( $atts ) {

    // normalize attribute keys, lowercase
    $atts = array_change_key_case((array)$atts, CASE_LOWER);

    // override default attributes with user attributes
    $args = shortcode_atts([
      'data' => '',
    ], $atts, 'quizdata');

    $data = $args['data'];

    switch( $data ) {
      case "quiztitle":
        $content = $this->quiz->getName();
        break;
      case "quizlink":
        $content = '<a href="' . $this->quiz->getPermalink() . '">';
        $content .= $this->quiz->getName();
        $content .= '</a>';
        break;
      case "scorelink":
        $content = '<a href="' . $this->score->getPermalink() . '">';
        $content .= 'Your Quiz Score';
        $content .= '</a>';
        break;
    }



    return $content;
  }

  public function addEmailTriggers() {
    add_action('quizmaster_completed_quiz', array( $this, 'sendEmailCompletedQuiz' ), 10, 2);
  }

  public function send() {
    wp_mail(
      $this->email->getRecipients(),
      $this->email->getSubject(),
      $this->email->getMessage(),
      $this->email->getHeaders()
    );
    print "Email Sent";
  }

  public function setMessage() {
    $msg = $this->parseTemplate();
    $this->email->setMessage( $msg );
  }

  public function parseTemplate() {
    $templateName = str_replace( '_', '-', $this->email->getKey() );
    $template = 'emails/' . $templateName;
    $content = quizmaster_parse_template( $template . '.php' );
    return do_shortcode( $content );
  }

  public function sendEmailCompletedQuiz( $quiz, $score ) {

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
      $this->email  = new QuizMaster_Model_Email( $emailPost->ID );
      $this->quiz   = $quiz;
      $this->score  = $score;
      $this->setMessage();
      $this->send();
    }

  }

}
