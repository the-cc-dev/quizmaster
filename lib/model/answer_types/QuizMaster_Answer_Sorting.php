<?php


class QuizMaster_Answer_Sorting extends QuizMaster_Model_Answer {

	protected $_answer_id;

  public function getKey() {
    return 'sorting';
  }

  public function getName() {
    return 'Sorting';
  }

  public function load( $data ) {

    $fieldAnswerData = $data['qmqe_answer_data'];
    $answerData = array();

    foreach( $fieldAnswerData as $fieldAnswer ) {
      $answer['answer'] = $fieldAnswer['qmqe_sorting_choice_answer'];
      $answerData[] = new self( $answer );
    }

    return $answerData;

  }

	public function getAnswerId() {
		return $this->_answer_id;
	}

	public function setAnswerId( $_answer_id ) {
		$this->_answer_id = $_answer_id;
	}

}
