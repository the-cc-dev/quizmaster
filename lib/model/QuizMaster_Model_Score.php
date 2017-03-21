<?php

class QuizMaster_Model_Score extends QuizMaster_Model_Model {

  protected $_id                  = 0;
  protected $_quizId              = 0;
  protected $_userId              = 0;
  protected $_scores              = array();
  protected $_totalQCount         = 0;
  protected $_totalQCorrect       = 0;
  protected $_totalQIncorrect     = 0;
  protected $_totalPointsPossible = 0;
  protected $_totalPointsEarned   = 0;
  protected $_totalTime           = 0;
  protected $_totalSolved         = 0;
  protected $_totalHints          = 0;
  protected $_totalsJson          = ''; // json string holding totals array
  protected $_post                = '';

  public function setTotalsJson( $totals ) {
    $this->_totalsJson = json_encode( $totals );
  }

  public function getTotalsJson() {
    return $this->_totalsJson;
  }

  public function setTotals( $totals ) {
    if( !is_array( $totals )) {
      $totals = json_decode( $totals, true );
    }
    $this->_totalQCount = $totals['qCount'];
    $this->_totalQCorrect = $totals['qCorrect'];
    $this->_totalQIncorrect = $totals['qIncorrect'];
    $this->_totalPointsPossible = $totals['pointsPossible'];
    $this->_totalPointsEarned = $totals['pointsEarned'];
    $this->_totalTime = $totals['time'];
    $this->_totalSolved = $totals['solved'];
    $this->_totalHints = $totals['hints'];
    $this->setTotalsJson( $this->getTotals() );
  }

  public function getTotals() {
    return array(
      'qCount'          => $this->_totalQCount,
      'qCorrect'        => $this->_totalQCorrect,
      'qIncorrect'      => $this->_totalQIncorrect,
      'pointsPossible'  => $this->_totalPointsPossible,
      'pointsEarned'    => $this->_totalPointsEarned,
      'time'            => $this->_totalTime,
      'solved'          => $this->_totalSolved,
      'hints'           => $this->_totalHints,
    );
  }

  public function getTotalQuestionCount() {
    return $this->_totalQCount;
  }

  public function getTotalQuestionsCorrect() {
    return $this->_totalQCorrect;
  }

  public function getTotalQuestionsIncorrect() {
    return $this->_totalQIncorrect;
  }

  public function getTotalPointsPossible() {
    return $this->_totalPointsPossible;
  }

  public function getPointsEarned() {
    return $this->_totalPointsEarned;
  }

  public function getTotalPointsEarned() {
    return $this->_totalPointsEarned;
  }

  public function getTotalTime() {
    return $this->_totalTime;
  }

  public function getTotalSolved() {
    return $this->_totalSolved;
  }

  public function getTotalHints() {
    return $this->_totalHints;
  }

  public function getCorrectRatio() {
    $totals = $this->getTotals();
    return $totals['qCorrect'] . '/' . $totals['qCount'];
  }

  public function setScores( $scores ) {
    $this->_scores = $scores;
  }

  public function setTotalSolved( $solved ) {
    $_totalSolved = $solved;
  }

  public function getScores( $format = 'objects' ) {
    switch( $format ) {
      case "objects":
        return $this->_scores;
        break;
      case "json":
        $scores = array();
        foreach( $this->_scores as $scoreQuestion ) {
          $scores[] = $scoreQuestion->outputArray();
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

  /*
   * @return quiz model object
   */
  public function getQuiz() {
    return new QuizMaster_Model_Quiz( $this->getQuizId() );
  }

  public function getQuizId() {
    return $this->_quizId;
  }

  public function getQuizName() {
    return get_the_title( $this->getQuizId());
  }

  public function setUserId($_userId) {
    $this->_userId = (int)$_userId;
    return $this;
  }

  public function getUserId() {
    return $this->_userId;
  }

  public function getUserName() {
    return get_userdata( $this->getUserId() )->user_login;
  }

  public function getCreateDate() {
    return get_post_time('Y-m-d', TRUE, $this->getId());
  }

  public function getCreateTime() {
    return get_post_time('U', TRUE, $this->getId());
  }

  public function save() {
    $this->createPost();
    $this->updateFields();
  }

  public function createPost() {
    $post = array(
      'post_type'     => 'quizmaster_score',
      'post_title'    => 'Score for Quiz #' . $this->getQuizId() . ' taken by User #' . $this->getUserId(),
      'post_status'   => 'publish',
      'post_author'   => 1,
    );
    $this->_id = wp_insert_post( $post );
  }

  public function updateFields() {
    update_field('qm_score_user', $this->getUserId(), $this->getID());
    update_field('qm_score_quiz', $this->getQuizId(), $this->getID());
    update_field('qm_score_scores', $this->getScores('json'), $this->getID());
    update_field('qm_score_totals', $this->getTotalsJson(), $this->getID());
  }

  /*
   * Override to alter the fields before setting model data
   */
  public function processFieldsDuringModelSet( $fields ) {

    $fields['quiz_id'] = $fields['quiz'];
    $fields['user_id'] = $fields['user'];
    $fields['scores'] = $this->loadScoreQuestionsFromJson( $fields['scores'] );
    $fields['totalsJson'] = $fields['totals'];
    return $fields;
  }

  public function loadScoreQuestionsFromJson( $scoreJson ) {
    $scoresArray = json_decode( $scoreJson, TRUE );
    $scores = array();
    if( empty($scoresArray)) {
      return $scores;
    }
    foreach( $scoresArray as $scoreSingle ) {
      $scores[] = new QuizMaster_Model_ScoreQuestion( $scoreSingle );
    }
    return $scores;
  }

  public function getFieldPrefix() {
    return 'qm_score_';
  }

  public function getScoreResult() {
    return round(( 100 * $this->getTotalPointsEarned() / $this->getTotalPointsPossible() ), 2);
  }

  public function getSolvedPercentage() {
    return round(( 100 * $this->getTotalSolved() / $this->getTotalQuestionCount() ), 2);
  }

  public function getQuestionsCorrectPercentage() {
    return round(( 100 * $this->getTotalQuestionsCorrect() / $this->getTotalQuestionCount() ), 2);
  }

  public function getQuestionsIncorrectPercentage() {
    return round(( 100 * $this->getTotalQuestionsIncorrect() / $this->getTotalQuestionCount() ), 2);
  }

}
