<?php


class QuizMaster_Answer_SingleChoice extends QuizMaster_Model_Answer {

  public function getKey() {
    return 'single';
  }

  public function getName() {
    return 'Single Choice';
  }

  public function load( $data ) {

    $fieldAnswerData = $data['qmqe_answer_data'];
    $answerData = array();

    foreach( $fieldAnswerData as $a ) {

      $answer['answer'] = $a[ 'qmqe_single_choice_answer' ];

			$answer['correct'] = 0;
			if( array_key_exists( 'qmqe_single_choice_correct', $a ) && $a[ 'qmqe_single_choice_correct' ][0] == 1 ) {
					$answer['correct'] = 1;
			}

			$answerData[] = new self( $answer );

    }

    return $answerData;

  }

	public function save( $answers ) {

	}

}
