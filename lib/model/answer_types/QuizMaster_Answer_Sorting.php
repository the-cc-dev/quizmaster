<?php


class QuizMaster_Answer_Sorting extends QuizMaster_Model_Answer {

  public function getKey() {
    return 'sorting';
  }

  public function getName() {
    return 'Sorting';
  }

  public function load( $data ) {

    $fieldAnswerData = $data['qmqe_sorting_choice_answers'];
    $answerData = array();

    foreach( $fieldAnswerData as $fieldAnswer ) {
      $answer['answer'] = $fieldAnswer['qmqe_sorting_choice_answer'];
      $answerData[] = new self( $answer );
    }
    return $answerData;

  }

}
