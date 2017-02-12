<?php

/**
 * @property array users
 * @property QuizMaster_Model_Quiz quiz
 */
class QuizMaster_View_Score extends QuizMaster_View_View {

  protected $_scoreQuestions = array();
  protected $_scoreTotals = array();
  protected $_activeScoreQuestion = '';
  protected $_activeQuestion = '';

  public function getScoreResult() {
    return round(( 100 * $this->getScoreTotal( 'points' ) / $this->getScoreTotal( 'possiblePoints' ) ), 2) . '%';
  }

  public function setActiveScoreQuestion( $scoreQuestion ) {
    $this->_activeScoreQuestion = $scoreQuestion;
    $this->loadQuestionById( $scoreQuestion->getQuestionId() );
  }

  public function getPossiblePoints() {
    $val = $this->_activeScoreQuestion->getPossiblePoints();
    $this->addScoreTotal('possiblePoints', $val);
    return $val;
  }

  public function getCorrectCount() {
    $val = $this->_activeScoreQuestion->getCorrectCount();
    $this->addScoreTotal('correctCount', $val);
    return $val;
  }

  public function getIncorrectCount() {
    $val = $this->_activeScoreQuestion->getIncorrectCount();
    $this->addScoreTotal('incorrectCount', $val);
    return $val;
  }

  public function getHintCount() {
    $val = $this->_activeScoreQuestion->getHintCount();
    $this->addScoreTotal('hintCount', $val);
    return $val;
  }

  public function getSolvedCount() {
    $val = $this->_activeScoreQuestion->getSolvedCount();
    $this->addScoreTotal('solvedCount', $val);
    return $val;
  }

  public function getQuestionTime() {
    $val = $this->_activeScoreQuestion->getQuestionTime();
    $this->addScoreTotal('questionTime', $val);
    return $val;
  }

  public function getPoints() {
    $val = $this->_activeScoreQuestion->getPoints();
    $this->addScoreTotal('points', $val);
    return $val;
  }

  public function getQuestion() {
    return $this->_activeQuestion->getQuestion();
  }

  public function addScoreTotal( $key, $value ) {
    $this->_scoreTotals[ $key ] += $value;
  }

  public function getScoreTotals() {
    return $this->_scoreTotals;
  }

  public function getScoreTotal( $key ) {
    return $this->_scoreTotals[ $key ];
  }

  public function setScoreQuestions( $scoreQuestions ) {
    $this->_scoreQuestions = $scoreQuestions;
  }

  public function getScoreQuestions() {
    return $this->_scoreQuestions;
  }

  public function loadQuestionById( $questionId ) {
    $this->_activeQuestion = new QuizMaster_Model_Question( $questionId );
  }

  public function listTable( $scores ) {
    return quizmaster_parse_template( 'reports/score-table.php',
      array(
        'scores' => $scores,
        'view' => $this,
      ));
  }

  public function getQuizTitle( $score ) {
    $quizId = $score->getQuizId();
    return get_the_title( $quizId );
  }

  public function getUserDisplayName( $score ) {
    $user = $score->getUserId();
  }

  public function getLink( $score, $text ) {
    return '<a href="' . get_permalink( $score->getId() ) . '">' . $text . '</a>';
  }

}
