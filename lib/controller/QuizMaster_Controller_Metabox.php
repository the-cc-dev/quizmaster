<?php

class QuizMaster_Controller_Metabox {

	public function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'init' ));
		add_action( 'save_post', array( $this, 'saveMeta' ), 10, 3 );

	}

	public function init() {

		$mb = $this->metaboxDefinitionQuiz();
		$this->addMetaBox( $mb );

		$mb = $this->metaboxDefinitionQuestion();
		$this->addMetaBox( $mb );

	}

	public function saveMeta( $postId ) {

		$postType = get_post_type( $postId );

		// make sure the post type belongs to quizmaster
		if ( 'quizmaster' != substr( $postType, 0, 10) ) return;

		$saveFunc = 'saveMeta' . ucfirst( substr( $postType, 11) );
		$this->{$saveFunc}( $postId );

	}

	public function saveMetaQuiz( $postId, $fieldGroupKey = 'quiz' ) {

		$fieldCtr = new QuizMaster_Controller_AdminFields();
		$fieldGroup = $fieldCtr->loadFieldGroup( $fieldGroupKey );

		foreach( $fieldGroup['fields'] as $field ) {

			// skip saving fields if save defined as false
			if( isset( $field['save'] ) && !$field['save'] ) {
				continue;
			}

			$key   = $field['key'];
			$value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_STRING );

			if ( $value ) {
				update_post_meta( $postId, $key, $value );
			}
			else {
				delete_post_meta( $postId, $key );
			}

		}

		quizmasterLog('before quizmaster_quiz_save 58');
		do_action( 'quizmaster_quiz_save', $postId );

	}

	public function saveMetaQuestion( $postId, $fieldGroupKey = 'question' ) {

		$fieldCtr = new QuizMaster_Controller_AdminFields();
		$fieldGroup = $fieldCtr->loadFieldGroup( $fieldGroupKey );
		$values = array();

		foreach( $fieldGroup['fields'] as $field ) {

			$key = $field['key'];

			// special handling for repeater fields
			if( $field['type'] == 'repeater' ) {
				$value = $this->getRepeaterValue( $field );
			} else {
				// default get value
				$value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_STRING );
			}

			// save meta unless field defined save false
			if( !isset( $field['save'] ) || $field['save'] ) {

				if ( $value ) {
					update_post_meta( $postId, $key, $value );
				}
				else {
					delete_post_meta( $postId, $key );
				}

			}

			// stash values of each field for additional processing
			$values[ $key ] = $value;

		}

		// process answer data
		switch( $values['qmqe_answer_type'] ) {
			case 'single':
				$values['qmqe_answer_data'] = $values['qmqe_single_choice_answers'];
				break;
			case 'multiple':
				$values['qmqe_answer_data'] = $values['qmqe_multiple_choice_answers'];
				break;
			case 'sort_answer':
				$values['qmqe_answer_data'] = $values['qmqe_sorting_choice_answers'];
				break;
			case 'free_choice':
				$values['qmqe_answer_data'] = $values['qmqe_free_choice_answers'];
				break;
			case 'fill_blanks':
				$values['qmqe_answer_data'] = $values['qmqe_fill_blanks'];
				break;
		}

		update_post_meta( $postId, 'qmqe_answer_data', $values['qmqe_answer_data'] );

		quizmasterLog('before quizmaster_question_save 58');
		do_action( 'quizmaster_question_save', $postId );

	}

	public function getRepeaterValue( $repeaterField ) {

		$value = filter_input( INPUT_POST, $repeaterField['key'], FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		return $value;

	}


	public function addMetabox( $mb ) {

		add_meta_box(
			$mb->id,
			$mb->title,
			$mb->callback,
			$mb->screen,
			$mb->context,
			$mb->priority
		);

	}

	public function metaboxQuiz() {

		global $quizmaster;
		$fieldCtr = $quizmaster->fields;

		$fieldCtr->setActiveFieldGroup( 'quiz' );

		$content = $fieldCtr->renderFieldGroup();

		print $content;
	}

	public function metaboxQuestion() {

		global $quizmaster;
		$fieldCtr = $quizmaster->fields;

		$fieldCtr->setActiveFieldGroup( 'question' );

		$content = $fieldCtr->renderFieldGroup();

		print $content;
	}

	public function metaboxDefinitionQuiz() {
		$mb = new stdClass();
		$mb->id = 'quizmaster-quiz-metabox';
		$mb->title = 'Quiz Settings';
		$mb->callback = array( $this, 'metaboxQuiz' );
		$mb->screen = 'quizmaster_quiz';
		$mb->context = 'normal';
		$mb->priority = 'high';
		return $mb;
	}

	public function metaboxDefinitionQuestion() {
		$mb = new stdClass();
		$mb->id = 'quizmaster-question-metabox';
		$mb->title = 'Quiz Settings';
		$mb->callback = array( $this, 'metaboxQuestion' );
		$mb->screen = 'quizmaster_question';
		$mb->context = 'normal';
		$mb->priority = 'high';
		return $mb;
	}

}
