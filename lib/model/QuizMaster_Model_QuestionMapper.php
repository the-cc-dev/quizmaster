<?php

class QuizMaster_Model_QuestionMapper extends QuizMaster_Model_Mapper {

  const QUESTION_TYPE_FIELD = "qmqe_answer_type";

  public function __construct() {
    parent::__construct();
  }

  public function save(QuizMaster_Model_Question $question) {

  }

  public function fetch( $qId ) {
    $qType = $this->questionTypeById( $qId );
    $qModel = $this->questionModelByType( $qType );

    if( $qModel ) {
      $q = new $qModel( $qId );
    } else {
      $q = new QuizMaster_Model_Question( $qId );
    }

    $q->loadAnswerData();

    return $q;
  }

  public function questionTypeById( $id ) {
    $qType = get_field( self::QUESTION_TYPE_FIELD, $id );
    if( !$qType || !isset( $qType ) ) {
      return false;
    }
    return $qType;
  }

  public function questionModelByType( $qType ) {
    $qTypes = array(
      'single' => 'QuizMaster_Question_SingleChoice',
      'multiple' => 'QuizMaster_Question_MultipleChoice',
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
