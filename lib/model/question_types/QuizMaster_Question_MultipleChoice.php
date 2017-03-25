<?php 

class QuizMaster_Question_MultipleChoice extends QuizMaster_Model_Question {

  public function answerModelName() {
    return "QuizMaster_Answer_MultipleChoice";
  }

  public function render() {
    quizmaster_get_template('question/multiple.php',
      array(
        'question' => $this,
      )
    );
  }

}
