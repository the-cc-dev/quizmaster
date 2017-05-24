<?php

class QuizMaster_Model_QuizQuestion extends QuizMaster_Model_Model {

	protected $_quizId = 0;
	protected $_questionId = 0;

	public static function associate( $quiz, $question ) {

		if ( $quiz instanceof QuizMaster_Model_Quiz ) {
		  $quizId = $quiz->getId();
		} else {
			$quizId = $quiz;
		}

		if ( $question instanceof QuizMaster_Model_Question ) {
		  $questionId = $question->getId();
		} else {
			$questionId = $question;
		}

		// QUIZMASTER_QUESTION_REFERENCE_FIELD

		// adds question to end of quiz
		$questionRow = array(
			QUIZMASTER_QUESTION_REFERENCE_FIELD => $questionId
		);
		add_row( QUIZMASTER_QUESTION_SELECTOR_FIELD, $questionRow, $quizId );
		$existingQuestions = get_field( QUIZMASTER_QUESTION_SELECTOR_FIELD, $quizId );

	}

}