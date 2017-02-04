<?php

class QuizMaster_Model_Score extends QuizMaster_Model_Model {

  protected $_id = 0;
  protected $_quizId = 0;
  protected $_userId = 0;
  protected $_questionScores = array();

  public function setQuestionScores( $questionScores ) {
    $this->_questionScores = $questionScores;
  }

  public function getQuestionScores( $format = 'array' ) {

    switch( $format ) {
      case "array":
        return $this->_questionScores;
        break;
      case "json":
        $qScores = array();
        foreach( $this->_questionScores as $qScore ) {
          $qScores[] = $qScore->outputArray();
        }
        return json_encode( $qScores );
        break;
    }

  }

  public function getId() {
    return $this->_id;
  }

  public function setStatisticRefId($_statisticRefId) {
    $this->_statisticRefId = (int)$_statisticRefId;
    return $this;
  }

  public function getStatisticRefId() {
    return $this->_statisticRefId;
  }

  public function setQuizId($_quizId) {
    $this->_quizId = (int)$_quizId;
    return $this;
  }

  public function getQuizId() {
    return $this->_quizId;
  }

  public function setUserId($_userId) {
    $this->_userId = (int)$_userId;
    return $this;
  }

  public function getUserId() {
    return $this->_userId;
  }

  public function getCreateTime() {
    return $this->_createTime;
  }

  public function save() {
    $this->createPost();
    $this->updateFields();
  }

  public function createPost() {
    $post = array(
      'post_type'     => 'quizmaster_score',
      'post_title'    => 'Score for Quiz ID',
      'post_status'   => 'publish',
      'post_author'   => 1,
    );
    $this->_id = wp_insert_post( $post );
  }

  public function updateFields() {
    update_field('qm_score_user', $this->getUserId(), $this->getID());
    update_field('qm_score_quiz', $this->getQuizId(), $this->getID());
    update_field('qm_score_questions', $this->getQuestionScores('json'), $this->getID());
  }

}
