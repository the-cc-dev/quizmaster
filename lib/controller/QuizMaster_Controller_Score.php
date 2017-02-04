<?php

class QuizMaster_Controller_Score extends QuizMaster_Controller_Controller {

  public function setAnswerData($_answerData) {
      $this->_answerData = $_answerData;

      return $this;
  }

  public function getAnswerData()
  {
      return $this->_answerData;
  }

  public function setSolvedCount($_solvedCount)
  {
      $this->_solvedCount = (int)$_solvedCount;

      return $this;
  }

  public function getSolvedCount() {
      return $this->_solvedCount;
  }


  /**
   *
   * @param QuizMaster_Model_Quiz $quiz
   * @return void|boolean
   */
  public function save($quiz = null) {

    $quizId = $this->_post['quizId'];
    $array = $this->_post['results'];
    $lockIp = $this->getIp();
    $userId = get_current_user_id();

    if ($lockIp === false) {
      return false;
    }

    if ($quiz === null) {
      $quizMapper = new QuizMaster_Model_QuizMapper();
      $quiz = $quizMapper->fetch($quizId);
    }

    if (!$quiz->isStatisticsOn()) {
      return false;
    }

    $questionScores = $this->makeDataList($quizId, $array, $quiz->getQuizModus());

    //var_dump( "Q SCORES" );
    //var_dump( $questionScores );


    if ($questionScores === false) {
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
    $score->setQuestionScores($questionScores);

    //$statisticRefMapper = new QuizMaster_Model_ScoreMapper();
    //$statisticRefMapper->statisticSave($score, $values);
    $score->save();

    return true;
  }

  private function makeDataList($quizId, $array, $modus) {

    $questionMapper = new QuizMaster_Model_QuestionMapper();

    $question = $questionMapper->fetchAllList($quizId, array('id', 'points'));
    $ids = array();

    foreach ($question as $q) {
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
      $avgTime = ceil($array['comp']['quizTime'] / count($question));
    }

    unset($array['comp']);

    $ak = array_keys($array);

    if (array_diff($ids, $ak) !== array_diff($ak, $ids)) {
      return false;
    }

    $values = array();

    foreach ($array as $k => $v) {
      $s = new QuizMaster_Model_ScoreQuestion();
      $s->setQuestionId($k);
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
    $statisticRefMapper = new QuizMaster_Model_StatisticRefMapper();

    $result = $statisticRefMapper->fetchFrontAvg($quizId);

    if (isset($result['g_points']) && $result['g_points']) {
      return round(100 * $result['points'] / $result['g_points'], 2);
    }

    return 0;
  }


}
