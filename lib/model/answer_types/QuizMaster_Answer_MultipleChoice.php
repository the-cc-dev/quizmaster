<?php


class QuizMaster_Answer_MultipleChoice extends QuizMaster_Model_Answer {

  public function getKey() {
    return 'multiple';
  }

  public function getName() {
    return 'Multiple Choice';
  }

  public function load( $data ) {

    $acfAnswerData = $data['qmqe_multiple_choice_answers'];
    $answerData = array();
    foreach( $acfAnswerData as $acfAnswer ) {
      $answer['answer'] = $acfAnswer['qmqe_multiple_choice_answer'];
      $answer['correct'] = $acfAnswer['qmqe_multiple_choice_correct'];
      $answerData[] = new QuizMaster_Model_AnswerTypes( $answer );
    }
    return $answerData;

  }

}
