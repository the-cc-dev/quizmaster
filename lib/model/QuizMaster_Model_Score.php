<?php

class QuizMaster_Model_Score extends QuizMaster_Model_Model {

  protected $_id          = 0;
  protected $_quizId      = 0;
  protected $_userId      = 0;
  protected $_scores      = array();

  public function setScores( $scores ) {
    $this->_scores = $scores;
  }

  public function getScores( $format = 'objects' ) {
    switch( $format ) {
      case "objects":
        return $this->_scores;
        break;
      case "json":
        $scores = array();
        foreach( $this->_scores as $score ) {
          $scores[] = $score->outputArray();
        }
        return json_encode( $scores );
        break;
    }

  }

  public function getId() {
    return $this->_id;
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

  // replace with post create time
  public function getCreateTime() {
    return $this->_createTime;
  }

  public function save() {
    $this->createPost();
    $this->updateFields();
  }

  public function createPost() {

    print "createPost running";

    $post = array(
      'post_type'     => 'quizmaster_score',
      'post_title'    => 'Score for Quiz #' . $this->getQuizId() . ' taken by User #' . $this->getUserId(),
      'post_status'   => 'publish',
      'post_author'   => 1,
    );
    $this->_id = wp_insert_post( $post );

    print $this->_id;
  }

  public function updateFields() {
    update_field('qm_score_user', $this->getUserId(), $this->getID());
    update_field('qm_score_quiz', $this->getQuizId(), $this->getID());
    update_field('qm_score_scores', $this->getScores('json'), $this->getID());
  }

  /*
   * Override to alter the fields before setting model data
   */
  public function processFieldsDuringModelSet( $fields ) {
    $fields['quiz_id'] = $fields['quiz'];
    $fields['user_id'] = $fields['user'];
    $fields['scores'] = $this->loadScoreQuestionsFromJson( $fields['scores'] );
    return $fields;
  }

  public function loadScoreQuestionsFromJson( $scoreJson ) {
    $scoresArray = json_decode( $scoreJson, TRUE );
    $scores = array();
    foreach( $scoresArray as $scoreSingle ) {
      $scores[] = new QuizMaster_Model_ScoreQuestion( $scoreSingle );
    }
    return $scores;
  }

  public function getFieldPrefix() {
    return 'qm_score_';
  }

}
