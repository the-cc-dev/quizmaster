<?php

class QuizMaster_Controller_Score extends QuizMaster_Controller_Controller {

  protected $_score = '';

  public function getScore() {
    return $this->_score;
  }

  public function setScore( $score ) {
    $this->_score = $score;
  }

  public function save($quiz = null) {

    $quizId = $this->_post['quizId'];
    $array = $this->_post['results'];

    // load revision
    $quizRevisions = wp_get_post_revisions( $quizId );
    $quizScoringRevision = reset( $quizRevisions );
    $quizId = $quizScoringRevision->ID;

    $lockIp = $this->getIp();
    $userId = get_current_user_id();

    if ($lockIp === false) {
      return false;
    }

    if ($quiz === null) {
      $quizMapper = new QuizMaster_Model_QuizMapper();
      $quiz = $quizMapper->fetch($quizId);
    }

    var_dump( $quiz );

    if (!$quiz->isStatisticsOn()) {
      print 37;
      return false;
    }

    $scores = $this->makeScoreList($quizId, $array, $quiz->getQuizModus());


    var_dump( "Q SCORES" );
    var_dump( $scores );


    if ($scores === false) {
      return false;
    }

    if ($quiz->getStatisticsIpLock() > 0) {

      $lockMapper = new QuizMaster_Model_LockMapper();
      $lockTime = $quiz->getStatisticsIpLock() * 60;

      $lockMapper->deleteOldLock($lockTime, $quiz->getId(), time(), QuizMaster_Model_Lock::TYPE_STATISTIC);

      if ($lockMapper->isLock($quizId, $lockIp, $userId, QuizMaster_Model_Lock::TYPE_STATISTIC)) {
        return false;
      }

      $lock = new QuizMaster_Model_Lock();
      $lock->setQuizId($quizId)
        ->setLockIp($lockIp)
        ->setUserId($userId)
        ->setLockType(QuizMaster_Model_Lock::TYPE_STATISTIC)
        ->setLockDate(time());

      $lockMapper->insert($lock);
    }

    // load score model
    $score = new QuizMaster_Model_Score();
    $score->setUserId($userId);
    $score->setQuizId($quizId);
    $score->setScores($scores);

    $totals = $this->calcTotals( $scores );
    $score->setTotals( $totals );

    print '<pre>';
    var_dump($score);
    print '</pre>';

    $score->save();

    return true;
  }

  private function calcTotals( $scores ) {

    $totals = array(
      'qCount'          => 0,
      'qCorrect'        => 0,
      'qIncorrect'      => 0,
      'pointsPossible'  => 0,
      'pointsEarned'    => 0,
      'time'            => 0,
      'hints'           => 0,
    );

    foreach( $scores as $score ) {
      $totals['qCorrect']         += $score->getCorrectCount();
      $totals['qIncorrect']       += $score->getIncorrectCount();
      $totals['pointsPossible']   += $score->getPossiblePoints();
      $totals['pointsEarned']     += $score->getPoints();
      $totals['time']             += $score->getQuestionTime();
      $totals['hints']            += $score->getHintCount();
    }

    $totals['qCount'] = $totals['qCorrect'] + $totals['qIncorrect'];

    var_dump($totals);

    return $totals;

  }

  private function makeScoreList($quizId, $array, $modus) {

    $questionMapper = new QuizMaster_Model_QuestionMapper();

    $questions = $questionMapper->fetchAllList($quizId, array('id', 'points'));

    $ids = array();

    foreach ($questions as $q) {
      if (!isset($array[$q['id']])) {
        continue;
      }

      $ids[] = $q['id'];
      $v = $array[$q['id']];

      if (!isset($v) || $v['points'] > $q['points'] || $v['points'] < 0) {
        return false;
      }
    }

    $avgTime = null;

    if ($modus == QuizMaster_Model_Quiz::QUIZ_MODUS_SINGLE) {
      $avgTime = ceil($array['comp']['quizTime'] / count($questions));
    }

    unset($array['comp']);

    $ak = array_keys($array);

    if (array_diff($ids, $ak) !== array_diff($ak, $ids)) {
      return false;
    }

    $values = array();

    foreach ($array as $k => $v) {

      // load revision
      $qRevisions = wp_get_post_revisions( $k );
      $qScoringRevision = reset( $qRevisions );
      $qId = $qScoringRevision->ID;

      $s = new QuizMaster_Model_ScoreQuestion();
      $s->setQuestionId($qId);
      $s->setHintCount(isset($v['tip']) ? 1 : 0);
      $s->setSolvedCount(isset($v['solved']) && $v['solved'] ? 1 : 0);
      $s->setCorrectCount($v['correct'] ? 1 : 0);
      $s->setIncorrectCount($v['correct'] ? 0 : 1);
      $s->setPoints($v['points']);
      $s->setQuestionTime($avgTime === null ? $v['time'] : $avgTime);
      $s->setAnswerData(isset($v['data']) ? $v['data'] : null);

      $values[] = $s;
    }

    return $values;
  }

  private function getIp() {
      if (get_current_user_id() > 0) {
          return '0';
      } else {
          return filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
      }
  }

  public function getAverageResult($quizId) {
    $scoreMapper = new QuizMaster_Model_ScoreMapper();

    /*
    $result = $scoreMapper->fetchFrontAvg($quizId);

    if (isset($result['g_points']) && $result['g_points']) {
      return round(100 * $result['points'] / $result['g_points'], 2);
    }
    */

    return 0;
  }

  public static function loadById( $id ) {

    $scoreCtr = new QuizMaster_Controller_Score;

    $post = get_post( $id );
    $fields = get_fields( $id );

    $score = new QuizMaster_Model_Score( $id );
    $scoreCtr->setScore( $score );

    return $scoreCtr;

  }


}
