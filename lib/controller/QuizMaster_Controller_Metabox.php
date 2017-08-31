<?php

class QuizMaster_Controller_Metabox {

	public function __construct() {
		add_action('add_meta_boxes', array( $this, 'init'));
	}

	public function init() {
		$quiz = $this->metaboxDefinitionQuiz();
		$this->addMetaBox( $quiz );
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
