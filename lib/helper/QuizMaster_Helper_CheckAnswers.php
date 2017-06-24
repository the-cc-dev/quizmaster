<?php

class QuizMaster_Helper_CheckAnswers {

	private $correct = false;
	private $points = 0;

	public function ajaxCheckAnswer() {

		$checkAnswers = new QuizMaster_Helper_CheckAnswers;
		$_POST = $_POST['data'];
		$quizId = $_POST['quizId'];
		$questionId = $_POST['questionId'];
		$userAnswerData = $_POST['userAnswerData'];
		$questionMapper = new QuizMaster_Model_QuestionMapper();
		$question = $questionMapper->fetch( $questionId );
		$answerType = $question->getAnswerType();

		print $answerType;

		// run check function based on answer type
		switch( $answerType ) {
			case 'single':
				$checkAnswers->checkSingle( $question, $userAnswerData );
				break;
			case 'multiple':
				$checkAnswers->checkMultiple( $question, $userAnswerData );
				break;
			case 'free_answer':
				$checkAnswers->checkFree( $question, $userAnswerData );
				break;
			case 'fill_blank':
				$checkAnswers->checkFillBlank( $question, $userAnswerData );
				break;
			case 'sort_answer':
				$checkAnswers->checkSorting( $question, $userAnswerData );
				break;
		}

		// return json check result data
		print json_encode( array(
				'correct' => $checkAnswers->correct,
				'points' 	=> $checkAnswers->points
			)
		);

		die();

	}

	public function checkSingle( $question, $userAnswerData ) {

		foreach( $question->getAnswerData() as $answerIndex => $answerObj ) {

			if( $answerObj->isCorrect() ) {

				// check if the user answer index matches this answer index
				// even if answers are randomized in display the indexes will continue to match the order set in the question
				if( $userAnswerData['answerIndexes'][0] == $answerIndex ) {
					$this->correct = 1;
					$this->points  = $question->getPoints();
				}

			}

		}

	}

	public function checkMultiple( $question, $userAnswerData ) {

		foreach( $question->getAnswerData() as $answerIndex => $answerObj ) {

			if( $answerObj->isCorrect() ) {

				// check if the user answer index matches this answer index
				// even if answers are randomized in display the indexes will continue to match the order set in the question
				if( ! in_array( $userAnswerData['answerIndexes'], $answerIndex )) {

					$this->correct = 0;
					$this->points  = 0;
					return;

				}

			}

		}

		// answer was correct
		$this->correct = 1;
		$this->points = $question->getPoints();

	}

	public function checkFree( $question, $userAnswerData ) {

		$answerData = $question->getAnswerData();
		$answer = $answerData[0]->getAnswer();

		if( $answer === $userAnswerData ) {

			$this->correct = 1;
			$this->points = $question->getPoints();

		}

	}

	public function checkFillBlank( $question, $userAnswerData ) {

	}

	public function checkSorting( $question, $userAnswerData ) {

		$answerData = $question->getAnswerData();
		var_dump( $answerData );

	}


}
