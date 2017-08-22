<?php

$fieldGroup = array (
	'key' => 'group_5885e433e5b83',
	'title' => 'QuizMaster Questions',
	'fields' => array (
		array (
			'placement' => 'left',
			'endpoint' => 0,
			'key' => 'field_5885ee4340905',
			'label' => 'Question',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
		),
		array (
			'layout' => 'horizontal',
			'choices' => array (
				'single' => 'Single Choice',
				'multiple' => 'Multiple Choice',
				'free_answer' => 'Free Choice',
				'sort_answer' => 'Sorting',
				'fill_blank' => 'Fill in the Blank',
			),
			'default_value' => '',
			'other_choice' => 0,
			'save_other_choice' => 0,
			'allow_null' => 0,
			'return_format' => 'value',
			'key' => 'field_5885e9f669c6f',
			'label' => 'Question Type',
			'name' => 'qmqe_answer_type',
			'type' => 'radio',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
		),
		array (
			'tabs' => 'all',
			'toolbar' => 'basic',
			'media_upload' => 1,
			'default_value' => '',
			'delay' => 0,
			'key' => 'field_5885e9029d26c',
			'label' => 'Question',
			'name' => 'qmqe_question',
			'type' => 'wysiwyg',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
		),
		array (
			'placement' => 'left',
			'endpoint' => 0,
			'key' => 'field_5885e9ec69c6e',
			'label' => 'Answers',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
		),
		array (
			'sub_fields' => array (
				array (
					'sub_fields' => array (
						array (
							'default_value' => '',
							'new_lines' => '',
							'maxlength' => '',
							'placeholder' => '',
							'rows' => 4,
							'key' => 'field_58ba579de6e64',
							'label' => 'Answer Text',
							'name' => 'qmqe_single_correct_answer',
							'type' => 'textarea',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id' => '',
							),
						),
					),
					'min' => 1,
					'max' => 1,
					'layout' => 'table',
					'button_label' => '',
					'collapsed' => '',
					'key' => 'field_58ba576ae6e63',
					'label' => 'Correct Answer',
					'name' => 'qmqe_single_correct_answer_repeater',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
				),
				array (
					'sub_fields' => array (
						array (
							'default_value' => '',
							'new_lines' => '',
							'maxlength' => '',
							'placeholder' => '',
							'rows' => 4,
							'key' => 'field_58ba57dc7ad9d',
							'label' => 'Answer Text',
							'name' => 'qmqe_single_incorrect_answer',
							'type' => 'textarea',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id' => '',
							),
						),
					),
					'min' => 1,
					'max' => 0,
					'layout' => 'table',
					'button_label' => 'Add Incorrect Answer',
					'collapsed' => '',
					'key' => 'field_58ba57dc7ad9c',
					'label' => 'Incorrect Answers',
					'name' => 'qmqe_single_incorrect_answer_repeater',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
				),
			),
			'min' => 1,
			'max' => 1,
			'layout' => 'block',
			'button_label' => 'Add Answer',
			'collapsed' => '',
			'key' => 'field_58b7897e6292d',
			'label' => 'Single Choice Answers',
			'name' => 'qmqe_single_choice_answers',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5885e9f669c6f',
						'operator' => '==',
						'value' => 'single',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
		),
		array (
			'sub_fields' => array (
				array (
					'default_value' => '',
					'new_lines' => '',
					'maxlength' => '',
					'placeholder' => '',
					'rows' => 4,
					'key' => 'field_5885edd008f8b',
					'label' => 'Answer',
					'name' => 'qmqe_multiple_choice_answer',
					'type' => 'textarea',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
				),
				array (
					'default_value' => 0,
					'message' => '',
					'ui' => 0,
					'ui_on_text' => '',
					'ui_off_text' => '',
					'key' => 'field_5885edec08f8c',
					'label' => 'Correct',
					'name' => 'qmqe_multiple_choice_correct',
					'type' => 'true_false',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
				),
			),
			'min' => 0,
			'max' => 0,
			'layout' => 'table',
			'button_label' => '',
			'collapsed' => '',
			'key' => 'field_5885edaf08f8a',
			'label' => 'Multiple Choice Answers',
			'name' => 'qmqe_multiple_choice_answers',
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
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
		),
		array (
			'sub_fields' => array (
				array(
					'key' => 'field_590eafc31392k',
					'label' => 'Answer ID',
					'name' => 'qmqe_sorting_choice_answer_id',
					'type' => 'text',
				),
				array (
					'tabs' => 'all',
					'toolbar' => 'basic',
					'media_upload' => 1,
					'default_value' => '',
					'delay' => 0,
					'key' => 'field_588a878e635dc',
					'label' => 'Answer',
					'name' => 'qmqe_sorting_choice_answer',
					'type' => 'wysiwyg',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
				),
			),
			'min' => 1,
			'max' => 0,
			'layout' => 'table',
			'button_label' => '',
			'collapsed' => '',
			'key' => 'field_588a878e635db',
			'label' => 'Sorting Choice Answers',
			'name' => 'qmqe_sorting_choice_answers',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5885e9f669c6f',
						'operator' => '==',
						'value' => 'sort_answer',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
		),
		array (
			'default_value' => '',
			'new_lines' => '',
			'maxlength' => '',
			'placeholder' => '',
			'rows' => 4,
			'key' => 'field_588a7ea280d53',
			'label' => 'Free Choice Answers',
			'name' => 'qmqe_free_choice_answers',
			'type' => 'textarea',
			'instructions' => 'Correct answers (one per line) (answers will be converted to lower case).',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5885e9f669c6f',
						'operator' => '==',
						'value' => 'free_answer',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
		),
		array (
			'default_value' => '',
			'new_lines' => '',
			'maxlength' => '',
			'placeholder' => '',
			'rows' => '',
			'key' => 'field_588a8e01fbde9',
			'label' => 'Fill in the Blank Answer',
			'name' => 'qmqe_fill_blanks',
			'type' => 'textarea',
			'instructions' => 'Enclose the searched words with { } e.g. "I {play} soccer". Capital and small letters will be ignored.
You can specify multiple options for a search word. Enclose the word with [ ] e.g. "I {[play][love][hate]} soccer" . In this case answers play, love OR hate are correct.
If mode "Different points for every answer" is activated, you can assign points with |POINTS. Otherwise 1 point will be awarded for every answer.
e.g. "I {play} soccer, with a {ball|3}" - "play" gives 1 point and "ball" 3 points.',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5885e9f669c6f',
						'operator' => '==',
						'value' => 'fill_blank',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
		),
		array (
			'placement' => 'left',
			'endpoint' => 0,
			'key' => 'field_5885e9d484139',
			'label' => 'Messages & Hints',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
		),
		array (
			'tabs' => 'all',
			'toolbar' => 'basic',
			'media_upload' => 1,
			'default_value' => '',
			'delay' => 0,
			'key' => 'field_5885e9405bc0f',
			'label' => 'Message for correct answer',
			'name' => 'qmqe_correct_msg',
			'type' => 'wysiwyg',
			'instructions' => 'This text will be visible if answered correctly. It can be used as explanation for complex questions. The message "Right" or "Wrong" is always displayed automatically.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
		),
		array (
			'tabs' => 'all',
			'toolbar' => 'basic',
			'media_upload' => 1,
			'default_value' => '',
			'delay' => 0,
			'key' => 'field_5885e9755bc10',
			'label' => 'Message for incorrect answer',
			'name' => 'qmqe_incorrect_msg',
			'type' => 'wysiwyg',
			'instructions' => 'This text will be visible if answered incorrectly. It can be used as explanation for complex questions. The message "Right" or "Wrong" is always displayed automatically.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
		),
		array (
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'key' => 'field_5885e99984137',
			'label' => 'Enable Hint?',
			'name' => 'qmqe_tip_enabled',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
		),
		array (
			'tabs' => 'all',
			'toolbar' => 'basic',
			'media_upload' => 1,
			'default_value' => '',
			'delay' => 0,
			'key' => 'field_5885e9b184138',
			'label' => 'Hint Message',
			'name' => 'qmqe_tip_msg',
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
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
		),
		array (
			'placement' => 'left',
			'endpoint' => 0,
			'key' => 'field_5885e8b50ce54',
			'label' => 'Scores',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
		),
		array (
			'default_value' => 1,
			'min' => '',
			'max' => '',
			'step' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'key' => 'field_5885e85a0ce51',
			'label' => 'Points',
			'name' => 'qmqe_points',
			'type' => 'number',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
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
			'key' => 'field_58bb45121c85b',
			'label' => 'Score Category',
			'name' => 'qmqe_score_category',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
		),
		array (
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'key' => 'field_5885e8850ce52',
			'label' => 'Different points for each answer',
			'name' => 'qmqe_answer_points_activated',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
		),
		array (
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'key' => 'field_5885e8a60ce53',
			'label' => 'Show points earned in the results messages?',
			'name' => 'qmqe_show_points_in_box',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
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
