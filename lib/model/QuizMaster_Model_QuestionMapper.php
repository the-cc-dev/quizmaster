<?php

class QuizMaster_Model_QuestionMapper extends QuizMaster_Model_Mapper {

    public function __construct() {
      parent::__construct();
    }

    public function save(QuizMaster_Model_Question $question) {

    }

    public function fetch( $id ) {
      $model = new QuizMaster_Model_Question( $id );
      return $model;
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

        $quizQuestionID = $qq[ QUIZMASTER_QUESTION_REFERENCE_FIELD ];
        $q = new QuizMaster_Model_Question( $quizQuestionID );
        $a[] = $q;

      }

      return $a;
    }

}
