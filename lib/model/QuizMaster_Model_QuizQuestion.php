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
		$questions = quizmaster_get_field( $quizId, QUIZMASTER_QUIZ_QUESTION_SELECTOR_FIELD );
		$questions[] = $questionId;
		update_post_meta( $quizId, QUIZMASTER_QUIZ_QUESTION_SELECTOR_FIELD, $questions );

		// adds quiz to list of selected quizzes associated from question editor
		$quizzes = quizmaster_get_field( $questionId, QUIZMASTER_QUIZ_QUESTION_SELECTOR_FIELD );
		$quizzes[] = $quizId;
		update_post_meta( $questionId, QUIZMASTER_QUIZ_QUESTION_SELECTOR_FIELD, $quizzes );

	}

	public function clearAssociatedQuizzesFromQuestion( $questionId, $quizId = false ) {

		if( $quizId == false ) {
			update_post_meta( $questionId, QUIZMASTER_QUESTION_QUIZ_SELECTOR_FIELD, array() );
		} else {
			// selective approach: remove one quiz
			$quizzes = quizmaster_get_field( $questionId, QUIZMASTER_QUESTION_QUIZ_SELECTOR_FIELD );
			$quizzes = quizmasterEnsureArray( $quizzes );

			if( empty( $quizzes )) {
				return;
			}

			if( ( $key = array_search( $quizId, $quizzes )) !== false ) {
				unset($quizzes[$key]);
			}
			update_post_meta( $questionId, QUIZMASTER_QUESTION_QUIZ_SELECTOR_FIELD, $quizzes );
		}

	}

	/*
	 * Remove one or more questions from the given quiz
	 */
	public function clearAssociatedQuestionsFromQuiz( $quizId, $questionId = false ) {

		if( $questionId == false ) {
			update_post_meta( $quizId, QUIZMASTER_QUIZ_QUESTION_SELECTOR_FIELD, array() );
		} else {

			// selective approach: remove one quiz
			$questions = quizmaster_get_field( $quizId, QUIZMASTER_QUIZ_QUESTION_SELECTOR_FIELD );

			if( !$questions ) {
				return;
			}

			if( !is_array( $questions )) {
				$questions = json_decode( $questions );
			}

			if( ( $key = array_search( $questionId, $questions )) !== false ) {
				unset($questions[$key]);
			}
			update_post_meta( $quizId, QUIZMASTER_QUIZ_QUESTION_SELECTOR_FIELD, $questions );

		}

	}

	/*
   * Associate question from quiz
   * Quiz selected from quiz tab on question editor
	 */
	public static function associateQuestionFromQuiz( $quiz, $question ) {

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

		// adds question to quiz
		$questions = quizmaster_get_field( $quizId, QUIZMASTER_QUIZ_QUESTION_SELECTOR_FIELD );
		if( !in_array( $questionId, $questions )) {
			$questions[] = $questionId;
			update_post_meta( $quizId, QUIZMASTER_QUIZ_QUESTION_SELECTOR_FIELD, $questions );
		}

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
		$quizzes = get_field( $questionId, QUIZMASTER_QUESTION_QUIZ_SELECTOR_FIELD );

		if( empty( $quizzes )) {
			return;
		}

		if( !in_array( $quizId, $quizzes )) {
			$quizzes[] = $quizId;
			update_post_meta( $questionId, QUIZMASTER_QUESTION_QUIZ_SELECTOR_FIELD, $quizzes );
		}

	}

}
