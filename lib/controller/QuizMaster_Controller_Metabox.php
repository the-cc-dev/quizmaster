<?php

class QuizMaster_Controller_Metabox {

	public function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'init' ));
		add_action( 'save_post', array( $this, 'saveMeta' ), 10, 3 );

	}

	public function init() {

		$quiz = $this->metaboxDefinitionQuiz();
		$this->addMetaBox( $quiz );

	}

	public function saveMeta( $postId ) {


		$postType = get_post_type( $postId );

		// make sure the post type belongs to quizmaster
		if ( 'quizmaster' != substr( $postType, 0, 10) ) return;

		$saveFunc = 'saveMeta' . ucfirst( substr( $postType, 11) );
		$this->{$saveFunc}( $postId );

	}

	public function saveMetaQuiz( $postId, $fieldGroupKey = 'quiz' ) {

		$fieldCtr = new QuizMaster_Controller_Fields();
		$fieldGroup = $fieldCtr->loadFieldGroup( $fieldGroupKey );

		foreach( $fieldGroup['fields'] as $field ) {

			$key   = $field['key'];
			$value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_STRING );

			/*
			if( $key == 'qmqu_result_text') {
				var_dump($key);
				var_dump($value);
				die();
			}
			*/



			if ( $value ) {
				update_post_meta( $postId, $key, $value );
			}
			else {
				delete_post_meta( $postId, $key );
			}

		}

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

}
