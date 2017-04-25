<?php


class QuizMaster_Answer_Sorting extends QuizMaster_Model_Answer {

  public function getKey() {
    return 'sorting';
  }

  public function getName() {
    return 'Sorting';
  }

  public function load( $data ) {

    $acfAnswerData = $data['qmqe_sorting_choice_answers'];
    $answerData = array();
    $acfAnswer = $acfAnswerData[0];

    // correct answer
    $rep = 'qmqe_sorting_correct_answer_repeater';
    $field = 'qmqe_sorting_correct_answer';
    $answer['answer'] = $acfAnswer[ $rep ][0][ $field ];
    $answer['correct'] = true;
    $answerData[] = new self( $answer );

    // incorrect answers
    $rep = 'qmqe_sorting_incorrect_answer_repeater';
    $field = 'qmqe_sorting_incorrect_answer';

    foreach( $acfAnswer[ $rep ] as $ia ) {

      $answer['answer'] = $ia[ $field ];
      $answer['correct'] = false;
      $answerData[] = new self( $answer );

    }

    return $answerData;

  }

}
