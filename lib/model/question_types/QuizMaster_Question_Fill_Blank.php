<?php

class QuizMaster_Question_Fill_Blank extends QuizMaster_Model_Question {

  public function answerModelName() {
    return "QuizMaster_Answer_Fill_Blank";
  }

  public function render() {
    quizmaster_get_template('question/fill_blank.php',
      array(
        'question' => $this,
      )
    );
  }

}
