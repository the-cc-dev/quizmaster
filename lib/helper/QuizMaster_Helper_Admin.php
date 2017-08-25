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

	}

	public function quiz_meta_box() {

		add_meta_box(
  	  'quizmaster_quiz_shortcode_metabox', // $id
  	  'Shortcode', // $title
  	  array( get_class(), 'quiz_meta_box_callback' ), // $callback
  	  'quizmaster_quiz', // $screen
  	  'side', // $context
  	  'low' // $priority
  	);

	}

	public function quiz_meta_box_callback( $post ) {

		print '[quizmaster id="'. $post->ID . '"]';

	}

	public function quiz_columns( $columns ) {
		return array_merge($columns,
			array(
				'shortcode'    => 'Shortcode',
			)
		);
	}

	public function quiz_column_content( $column, $post_id ) {

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

	public function score_columns( $columns ) {
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

	public function score_column_content( $column, $post_id ) {

	  $score = new QuizMaster_Model_Score( $post_id );

	  switch ( $column ) {
	    case 'quiz' :
	      $quizRevisionId = get_field( $score->getFieldPrefix() . 'quiz_revision', $post_id );
	      print get_the_title( $quizRevisionId );
	      break;
	    case 'user' :
	      $user = get_field( $score->getFieldPrefix() . 'user', $post_id );
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

	public function score_sortable_column( $columns ) {
	  $columns['quiz']   = 'quiz';
	  $columns['user']   = 'user';
	  $columns['points'] = 'points';
	  return $columns;
	}

}
