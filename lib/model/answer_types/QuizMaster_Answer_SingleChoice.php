<?php


class QuizMaster_Answer_SingleChoice extends QuizMaster_Model_Answer {

  public function getKey() {
    return 'single';
  }

  public function getName() {
    return 'Single Choice';
  }

  public function load( $data ) {

    $acfAnswerData = $data['qmqe_single_choice_answers'];
    $answerData = array();
    $acfAnswer = $acfAnswerData[0];

    // correct answer
    $rep = 'qmqe_single_correct_answer_repeater';
    $field = 'qmqe_single_correct_answer';
    $answer['answer'] = $acfAnswer[ $rep ][0][ $field ];
    $answer['correct'] = true;
    $answerData[] = new self( $answer );

    // incorrect answers
    $rep = 'qmqe_single_incorrect_answer_repeater';
    $field = 'qmqe_single_incorrect_answer';

    foreach( $acfAnswer[ $rep ] as $ia ) {

      $answer['answer'] = $ia[ $field ];
      $answer['correct'] = false;
      $answerData[] = new self( $answer );

    }

    return $answerData;

  }

	public function save( $answers ) {
		
	}

}
