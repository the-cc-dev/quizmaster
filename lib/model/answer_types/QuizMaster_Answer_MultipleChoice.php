<?php
class QuizMaster_Answer_MultipleChoice extends QuizMaster_Model_Answer {

	public function getKey() {
    return 'multiple';
  }

  public function getName() {
    return 'Multiple Choice';
  }

  public function load( $data ) {

    $fieldAnswerData = $data['qmqe_answer_data'];

    $answerData = array();
    foreach( $fieldAnswerData as $a ) {
      $answer['answer'] = $a['qmqe_multiple_choice_answer'];

			$answer['correct'] = 0;
			if( array_key_exists( 'qmqe_multiple_choice_correct', $a ) && $a[ 'qmqe_multiple_choice_correct' ][0] == 1 ) {
					$answer['correct'] = 1;
			}

      $answerData[] = new QuizMaster_Model_AnswerTypes( $answer );
    }
    return $answerData;
  }
}
