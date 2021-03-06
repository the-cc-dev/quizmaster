<?php

class QuizMaster_Model_QuestionMapper extends QuizMaster_Model_Mapper {

  const QUESTION_TYPE_FIELD = "qmqe_answer_type";

  public function __construct() {
    parent::__construct();
  }

  public static function fetch( $qId ) {

    $qType = self::questionTypeById( $qId );
    $qModel = self::questionModelByType( $qType );

    if( $qModel ) {
      $q = new $qModel( $qId );
    } else {
			return wp_error( 'Question Model could not be loaded for ' . $qId );
		}

    return $q;
  }

  public static function questionTypeById( $id ) {


    $qType = quizmaster_get_field( $id, self::QUESTION_TYPE_FIELD );

    if( !$qType || !isset( $qType ) ) {
      return false;
    }

    return $qType;

  }

  public static function questionModelByType( $qType ) {

    $qTypes = array(
      'single'        => 'QuizMaster_Question_SingleChoice',
      'multiple'      => 'QuizMaster_Question_MultipleChoice',
      'free_answer'   => 'QuizMaster_Question_Free',
      'sort_answer'   => 'QuizMaster_Question_Sorting',
      'fill_blank'  	=> 'QuizMaster_Question_FillBlank',
    );

    $qTypes = apply_filters('quizmaster_question_type_registry', $qTypes );

    // check if this question type exists in the register
    if( !array_key_exists( $qType, $qTypes ) ) {
      return false;
    }

    return $qTypes[ $qType ];
  }

  /**
   * @param $quizId
   * @param bool $rand
   * @param int $max
   *
   * @return QuizMaster_Model_Question[]
   */
  public function fetchAll( $quizId ) {

    $questions = array();

		$quiz = new QuizMaster_Model_Quiz( $quizId );

    $quizQuestions = quizmaster_get_field( $quizId, QUIZMASTER_QUIZ_QUESTION_SELECTOR_FIELD );
		$quizQuestions = json_decode( $quizQuestions );

    if( empty($quizQuestions)) {
      return false;
    }

		foreach( $quizQuestions as $questionId ) {
			$questions[] = $this->fetch( $questionId );
		}

    return $questions;
  }

}
