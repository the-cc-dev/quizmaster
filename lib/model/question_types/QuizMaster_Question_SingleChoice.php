<?php

class QuizMaster_Question_SingleChoice extends QuizMaster_Model_Question {

  public function answerModelName() {
    return "QuizMaster_Answer_SingleChoice";
  }

  public function render() {
    quizmaster_get_template('question/single.php',
      array(
        'question' => $this,
      )
    );
  }

}
