<?php

class QuizMaster_Helper_Admin {

	public static function init() {

		/* Quiz Metaboxes */
		add_action( 'add_meta_boxes_quizmaster_quiz', array( get_class(), 'quiz_meta_box' ));

		/* Quiz Columns */
		add_filter('manage_quizmaster_quiz_posts_columns', array( get_class(), 'quiz_columns' ));
		add_filter('manage_quizmaster_quiz_posts_custom_column', array( get_class(), 'quiz_column_content' ), 10, 2);
		//add_filter('manage_edit-quizmaster_quiz_sortable_columns', array( get_class(), 'score_sortable_column' ));

		/* Quiz Score Columns */
		add_filter('manage_quizmaster_score_posts_columns', array( get_class(), 'score_columns' ));
		add_filter('manage_quizmaster_score_posts_custom_column', array( get_class(), 'score_column_content' ), 10, 2);
		add_filter('manage_edit-quizmaster_score_sortable_columns', array( get_class(), 'score_sortable_column' ));

		// do question and quiz association on save_post
		add_action( 'quizmaster_question_save', array( get_class(), 'question_associate_quizzes'), 5 );
		add_action( 'quizmaster_quiz_save', array( get_class(), 'quiz_associate_questions'), 5 );

	}


	public static function question_associate_quizzes( $questionId ) {

		// data setup
		$currentQuizzes = quizmaster_get_field( $questionId, QUIZMASTER_QUESTION_QUIZ_SELECTOR_FIELD );
		$quizQuestion = new QuizMaster_Model_QuizQuestion;

		$currentQuizzes = json_decode( $currentQuizzes );

		// nothing to do if old and new are both empty
		if( empty($newQuizzes) && empty($currentQuizzes)) {
			return;
		}

		// disassociate questions removed from quiz question list
		if( !empty( $currentQuizzes )) {
			if( empty( $newQuizzes )) {
				$quizzesRemoved = $currentQuizzes;
			} else {
				$quizzesRemoved = array_diff( $currentQuizzes, $newQuizzes );
			}

			if( !empty( $quizzesRemoved )) {
				foreach( $quizzesRemoved as $quizId ) {
					$quizQuestion->clearAssociatedQuestionsFromQuiz( $quizId, $questionId );
				}
			}

		}

		// associate quizzes added to the quiz tab from question editor
		if( !empty( $newQuizzes )) {

			if( empty( $currentQuizzes )) {
				$quizzesAdded = $newQuizzes;
			} else {
				$quizzesAdded = array_diff( $newQuizzes, $currentQuizzes );
			}

			if( !empty( $quizzesAdded )) {
				foreach( $quizzesAdded as $quizId ) {
					$quizQuestion->associateQuestionFromQuiz( $quizId, $questionId );
				}
			}

		}

	}


	public static function quiz_associate_questions( $quizId ) {

		// data setup
		$currentQuestions = quizmaster_get_field( $quizId, QUIZMASTER_QUIZ_QUESTION_SELECTOR_FIELD );
		$quizQuestion = new QuizMaster_Model_QuizQuestion;

		if( empty($newQuestions) && empty($currentQuestions)) {
			return; // nothing to do
		}

		// disassociate questions removed from quiz question list
		if( !empty( $currentQuestions )) {

			if( !empty( $newQuestions )) {
				$questionsRemoved = array_diff( $currentQuestions, $newQuestions );
			} else {
				$questionsRemoved = json_decode( $currentQuestions );
			}

			foreach( $questionsRemoved as $questionId ) {
				$quizQuestion->clearAssociatedQuizzesFromQuestion( $questionId, $quizId );
			}

		}

		if( !empty( $newQuestions )) {

			// associate questions added to quiz question list
			if( !empty( $currentQuestions )) {
				$questionsAdded = array_diff( $newQuestions, $currentQuestions );
			} else {
				$questionsAdded = $newQuestions;
			}

			if( !empty( $questionsAdded )) {

				foreach( $questionsAdded as $questionId ) {
					$quizQuestion->associateQuizFromQuestion( $quizId, $questionId );
				}

			}

		}

	}

	public static function quiz_meta_box() {

		add_meta_box(
  	  'quizmaster_quiz_shortcode_metabox', // $id
  	  'Shortcode', // $title
  	  array( get_class(), 'quiz_meta_box_callback' ), // $callback
  	  'quizmaster_quiz', // $screen
  	  'side', // $context
  	  'low' // $priority
  	);

	}

	public static function quiz_meta_box_callback( $post ) {

		print '[quizmaster id="'. $post->ID . '"]';

	}

	public static function quiz_columns( $columns ) {
		return array_merge($columns,
			array(
				'shortcode'    => 'Shortcode',
			)
		);
	}

	public static function quiz_column_content( $column, $post_id ) {

	  $quiz = new QuizMaster_Model_Quiz( $post_id );

	  switch ( $column ) {
	    case 'shortcode' :
	      print '[quizmaster id="' . $quiz->getId() . '"]';
	      break;
	  }
	}

	/*
   * Score Columns
	 */

	public static function score_columns( $columns ) {
		return array_merge($columns,
			array(
				'date'		=> 'Taken At',
				'quiz'    => 'Quiz',
				'user'    => 'User',
				'points'  => 'Points',
				'correct' => 'Correct'
			)
		);
	}

	public static function score_column_content( $column, $post_id ) {

	  $score = new QuizMaster_Model_Score( $post_id );

	  switch ( $column ) {
	    case 'quiz' :
	      $quizRevisionId = quizmaster_get_field( $post_id, $score->getFieldPrefix() . 'quiz_revision' );
	      print get_the_title( $quizRevisionId );
	      break;
	    case 'user' :
	      $user = quizmaster_get_field( $post_id, $score->getFieldPrefix() . 'user' );
				if( is_array( $user )) {
					print $user['display_name'];
				} else {
					print __('anonymous', 'quizmaster');
				}
	      break;
	    case 'points' :
	      $totals = $score->getTotals();
	      print $totals['pointsEarned'];
	      break;
	    case 'correct' :
	      $totals = $score->getTotals();
	      print $totals['qCorrect'] . '/' . $totals['qCount'];
	      break;
	  }
	}

	public static function score_sortable_column( $columns ) {
	  $columns['quiz']   = 'quiz';
	  $columns['user']   = 'user';
	  $columns['points'] = 'points';
	  return $columns;
	}

}
