<?php

class QuizMaster_Model_QuestionMapper extends QuizMaster_Model_Mapper {

  const QUESTION_TYPE_FIELD = "qmqe_answer_type";

  public function __construct() {
    parent::__construct();
  }

  public function save(QuizMaster_Model_Question $question) {

  }

  public static function fetch( $qId ) {

		die(9999);

    $qType = self::questionTypeById( $qId );

		var_dump(19);
		var_dump($qType);
		die();

    $qModel = self::questionModelByType( $qType );

    if( $qModel ) {
      $q = new $qModel( $qId );
    } else {
			return wp_error( 'Question Model could not be loaded for ' . $qId );
		}

    $q->loadAnswerData();

    return $q;
  }

  public static function questionTypeById( $id ) {

		if( wp_is_post_revision( $id )) {

		}

    $qType = get_field( self::QUESTION_TYPE_FIELD, $id );

		var_dump(40);
		var_dump($qType);

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
      'cloze_answer'  => 'QuizMaster_Question_FillBlank',
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

    $a = array();

    $quizPost = get_post( $quizId );
    $quizQuestions = get_field( QUIZMASTER_QUESTION_SELECTOR_FIELD, $quizId );

    if( empty($quizQuestions)) {
      return false;
    }

    foreach( $quizQuestions as $qq ) {
      $qId = $qq[ QUIZMASTER_QUESTION_REFERENCE_FIELD ];
      $a[] = $this->fetch( $qId );
    }

    return $a;
  }

}
