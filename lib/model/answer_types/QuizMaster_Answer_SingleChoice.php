<?php


class QuizMaster_Answer_SingleChoice extends QuizMaster_Model_Answer {

  public function getKey() {
    return 'single';
  }

  public function getName() {
    return 'Single Choice';
  }

}
