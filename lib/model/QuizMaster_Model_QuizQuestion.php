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

		// adds question to end of quiz
		$questions = get_field( QUIZMASTER_QUIZ_QUESTION_SELECTOR_FIELD, $quizId );
		$questions[] = $questionId;
		update_field( QUIZMASTER_QUIZ_QUESTION_SELECTOR_FIELD, $questions, $quizId );

		// adds quiz to list of selected quizzes associated from question editor
		$quizzes = get_field( QUIZMASTER_QUIZ_QUESTION_SELECTOR_FIELD, $questionId );
		$quizzes[] = $quizId;
		update_field( QUIZMASTER_QUIZ_QUESTION_SELECTOR_FIELD, $quizzes, $questionId );

	}

	public function clearAssociatedQuizzesFromQuestion( $questionId, $quizId = false ) {

		if( $quizId == false ) {
			update_field( QUIZMASTER_QUESTION_QUIZ_SELECTOR_FIELD, array(), $questionId );
		} else {
			// selective approach: remove one quiz
			$quizzes = get_field( QUIZMASTER_QUESTION_QUIZ_SELECTOR_FIELD, $questionId );
			unset( $quizzes[$quizId] );
			update_field( QUIZMASTER_QUESTION_QUIZ_SELECTOR_FIELD, $quizzes, $questionId );
		}

	}

	public function clearAssociatedQuestionsFromQuiz( $quizId ) {
		update_field( QUIZMASTER_QUIZ_QUESTION_SELECTOR_FIELD, array(), $quizId );
	}

	public static function associateQuizFromQuestion( $quiz, $question ) {

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

		// adds quiz to list of selected quizzes associated from question editor
		$quizzes = get_field( QUIZMASTER_QUESTION_QUIZ_SELECTOR_FIELD, $questionId );
		$quizzes[] = $quizId;
		update_field( QUIZMASTER_QUESTION_QUIZ_SELECTOR_FIELD, $quizzes, $questionId );

	}

}
