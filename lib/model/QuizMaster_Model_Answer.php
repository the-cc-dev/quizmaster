<?php

class QuizMaster_Model_Answer extends QuizMaster_Model_Model {

    protected $_answer  = '';
    protected $_points  = 1;
    protected $_correct = false;

    public function setAnswer( $_answer ) {
      $this->_answer = (string) $_answer;
      return $this;
    }

    public function getAnswer() {
      return $this->_answer;
    }

    public function setPoints( $_points ) {
      $this->_points = (int) $_points;
      return $this;
    }

    public function getPoints() {
      return $this->_points;
    }

    public function setCorrect( $_correct ) {
      $this->_correct = (bool) $_correct;
      return $this;
    }

    public function isCorrect() {
      return $this->_correct;
    }

}
