<?php

$fieldGroup = array (
	'key' => 'question',
	'title' => 'QuizMaster Questions',
	'fields' => array (
		array (
			'placement' => 'left',
			'key' => 'qmqe_settings',
			'label' => 'Question',
			'type' => 'tab',
		),
		array (
			'tabs' => 'all',
			'toolbar' => 'basic',
			'media_upload' => 1,
			'key' => 'qmqe_question',
			'label' => 'Question',
			'type' => 'wysiwyg',
		),

		// answer tab
		array (
			'placement' => 'left',
			'key' => 'answers',
			'label' => 'Answers',
			'type' => 'tab',
		),
		array (
		 'label' => 'Answer Data',
		 'key' => 'qmqe_answer_data',
		 'type' => 'hidden',
	  ),

		/*
     * answer tab fields
		 */
		 array (
 			'layout' => 'horizontal',
 			'choices' => array (
 				'single' => 'Single Choice',
 				'multiple' => 'Multiple Choice',
 			),
 			'default_value' => 'single',
 			'return_format' => 'value',
 			'key' => 'qmqe_answer_type',
 			'label' => 'Answer Type',
 			'type' => 'radio',
 			'instructions' => '',
 			'required' => 0,
 		),


		// single choice
		array (
			'sub_fields' => array (
				array (
					'key' => 'qmqe_single_choice_answer',
					'label' => 'Answer',
					'type' => 'text',
				),
				array (
					'label' => 'Correct',
					'key' => 'qmqe_single_choice_correct',
					'type' => 'true_false',
				),
			),
			'min' => 1,
			'max' => 1,
			'layout' => 'block',
			'button_label' => 'Add Answer',
			'collapsed' => '',
			'label' => 'Single Choice Answers',
			'key' => 'qmqe_single_choice_answers',
			'type' => 'repeater',
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5885e9f669c6f',
						'operator' => '==',
						'value' => 'single',
					),
				),
			),
		),

		// multiple choice answers
		array (
			'sub_fields' => array (
				array (
					'key' => 'qmqe_multiple_choice_answer',
					'label' => 'Answer',
					'type' => 'text',
				),
				array (
					'label' => 'Correct',
					'key' => 'qmqe_multiple_choice_correct',
					'type' => 'true_false',
				),
			),
			'min' => 0,
			'max' => 0,
			'layout' => 'table',
			'button_label' => '',
			'collapsed' => '',
			'label' => 'Multiple Choice Answers',
			'key' => 'qmqe_multiple_choice_answers',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5885e9f669c6f',
						'operator' => '==',
						'value' => 'multiple',
					),
				),
			),
		),
		array (
			'placement' => 'left',
			'key' => 'messages_hints',
			'label' => 'Messages & Hints',
			'type' => 'tab',
		),
		array (
			'tabs' => 'all',
			'toolbar' => 'basic',
			'media_upload' => 1,
			'default_value' => '',
			'label' => 'Message for correct answer',
			'key' => 'qmqe_correct_msg',
			'type' => 'wysiwyg',
			'instructions' => 'This text will be visible if answered correctly. It can be used as explanation for complex questions. The message "Right" or "Wrong" is always displayed automatically.',
		),
		array (
			'tabs' => 'all',
			'toolbar' => 'basic',
			'media_upload' => 1,
			'default_value' => '',
			'delay' => 0,
			'label' => 'Message for incorrect answer',
			'key' => 'qmqe_incorrect_msg',
			'type' => 'wysiwyg',
			'instructions' => 'This text will be visible if answered incorrectly. It can be used as explanation for complex questions. The message "Right" or "Wrong" is always displayed automatically.',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'default_value' => 0,
			'message' => '',
			'label' => 'Enable Hint?',
			'key' => 'qmqe_tip_enabled',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
		),
		array (
			'tabs' => 'all',
			'toolbar' => 'basic',
			'media_upload' => 1,
			'default_value' => '',
			'delay' => 0,
			'label' => 'Hint Message',
			'key' => 'qmqe_tip_msg',
			'type' => 'wysiwyg',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5885e99984137',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
		),
		array (
			'placement' => 'left',
			'key' => 'scores',
			'label' => 'Scores',
			'type' => 'tab',
		),
		array (
			'default_value' => 1,
			'label' => 'Points',
			'key' => 'qmqe_points',
			'type' => 'number',
			'instructions' => '',
			'required' => 0,
		),
		array (
			'taxonomy' => 'quizmaster_question_category',
			'field_type' => 'select',
			'multiple' => 0,
			'allow_null' => 0,
			'return_format' => 'id',
			'add_term' => 1,
			'load_terms' => 0,
			'save_terms' => 0,
			'label' => 'Score Category',
			'key' => 'qmqe_score_category',
			'type' => 'taxonomy',
		),
		array (
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'label' => 'Different points for each answer',
			'key' => 'qmqe_answer_points_activated',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'default_value' => 0,
			'label' => 'Show points earned in the results messages?',
			'key' => 'qmqe_show_points_in_box',
			'type' => 'true_false',
		),

		array (
			'placement' => 'left',
			'key' => 'quizzes',
			'label' => 'Quizzes',
			'type' => 'tab',
			'instructions' => 'Attach this question to quizzes.',
		),

		array (
			'post_type' => array (
				0 => 'quizmaster_quiz',
			),
			'multiple' => 1,
			'return_format' => 'id',
			'ui' => 1,
			'key' => 'qmqe_quizzes',
			'label' => 'Quizzes',
			'key' => 'qmqe_quizzes',
			'type' => 'relationship',
			'instructions' => 'Select quizzes from the quiz list on the left by clicking on them. Quizzes disabled from selection already include this quiz. Questions will appear at the end of the quiz, unless randomization settings for the quiz are applied. Edit the quiz question list directly to reorder the question list.',
			'filters' => array (
				0 => 'search',
			),
			'selection_title' => 'Available Quizzes',
			'selected_title' => 'Assigned Quizzes'
		),
	),

	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'quizmaster_question',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => array (
		0 => 'permalink',
		1 => 'the_content',
		2 => 'excerpt',
		3 => 'custom_fields',
		4 => 'discussion',
		5 => 'comments',
		6 => 'revisions',
		7 => 'slug',
		8 => 'author',
		9 => 'format',
		10 => 'page_attributes',
		11 => 'featured_image',
		12 => 'categories',
		13 => 'tags',
		14 => 'send-trackbacks',
	),
	'active' => 1,
	'description' => '',
);
