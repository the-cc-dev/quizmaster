<?php

$fieldGroup = array (
	'key' => 'group_58850e7d30db8',
	'title' => 'QuizMaster Quiz',
	'fields' => array (
		array (
			'placement' => 'left',
			'endpoint' => 0,
			'key' => 'field_5885c9ee166e4',
			'label' => 'Quiz Mode',
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
			'layout' => 'vertical',
			'choices' => array (
				0 => 'Normal',
				1 => 'Normal + Back-Button',
				2 => 'Check -> continue',
				3 => 'Questions below each other',
			),
			'default_value' => 0,
			'other_choice' => 0,
			'save_other_choice' => 0,
			'allow_null' => 0,
			'return_format' => 'value',
			'key' => 'field_5885ca04166e5',
			'label' => 'Normal',
			'name' => 'qmqu_quiz_modus',
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
			'placement' => 'left',
			'endpoint' => 0,
			'key' => 'field_58850eff6e064',
			'label' => 'Basic Options',
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
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'key' => 'field_58850ed66e063',
			'label' => 'Hide Quiz Title',
			'name' => 'qmqu_title_hidden',
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
			'min' => '',
			'max' => '',
			'step' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => 'Seconds',
			'key' => 'field_5885e013a7505',
			'label' => 'Time Limit',
			'name' => 'qmqu_time_limit',
			'type' => 'number',
			'instructions' => 'Enter 0 for no time limit.',
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
			'key' => 'field_5885e0ab55816',
			'label' => 'Randomize Questions',
			'name' => 'qmqu_question_random',
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
			'key' => 'field_5885e0c755817',
			'label' => 'Randomize Answers',
			'name' => 'qmqu_answer_random',
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
			'key' => 'field_58850f3169dab',
			'label' => 'Hide "Restart quiz" button',
			'name' => 'qmqu_btn_restart_quiz_hidden',
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
			'key' => 'field_58850f7f69dac',
			'label' => 'Hide "View question" button',
			'name' => 'qmqu_btn_view_question_hidden',
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
			'key' => 'field_588c5d5499c2d',
			'label' => 'Show only specific number of questions',
			'name' => 'qmqu_show_max_question',
			'type' => 'true_false',
			'instructions' => 'If you enable this option, maximum number of displayed questions will be X from X questions. (The output of questions is random)',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
		),
		array (
			'default_value' => 10,
			'min' => '',
			'max' => '',
			'step' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'key' => 'field_588c5d9699c2e',
			'label' => 'Maximum number of questions',
			'name' => 'qmqu_show_max_question_value',
			'type' => 'number',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_588c5d5499c2d',
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
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'key' => 'field_588c5e56c9a0b',
			'label' => 'Question overview',
			'name' => 'qmqu_show_review_question',
			'type' => 'true_false',
			'instructions' => 'Add at the top of the quiz a question overview, which allows easy navigation. Additional questions can be marked "to review". Additional quiz overview will be displayed, before quiz is finished.',
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
			'key' => 'field_588c5ea11780c',
			'label' => 'Autostart',
			'name' => 'qmqu_autostart',
			'type' => 'true_false',
			'instructions' => 'If you enable this option, the quiz will start automatically after the page is loaded.',
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
			'key' => 'field_5885cba6df41e',
			'label' => 'Quiz Description',
			'name' => 'qmqu_quiz_description',
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
			'key' => 'field_588c5c9acb1ae',
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
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'key' => 'field_5885ddbbdb9a0',
			'label' => 'Activate Score Tracking',
			'name' => 'qmqu_statistics_on',
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
			'min' => '',
			'max' => '',
			'step' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => 'minutes',
			'key' => 'field_5885e10109f2f',
			'label' => 'Score IP Lock',
			'name' => 'qmqu_statistics_ip_lock',
			'type' => 'number',
			'instructions' => 'Protect the score system from spam. Score for a quiz taken by user will only be saved every X minutes from same IP. (0 = deactivated)',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5885ddbbdb9a0',
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
			'taxonomy' => 'quizmaster_quiz_category',
			'field_type' => 'select',
			'multiple' => 0,
			'allow_null' => 0,
			'return_format' => 'id',
			'add_term' => 1,
			'load_terms' => 0,
			'save_terms' => 0,
			'key' => 'field_58bb3e9febdb5',
			'label' => 'Score Category',
			'name' => 'qmqu_score_category',
			'type' => 'taxonomy',
			'instructions' => 'Choose a category for scoring purposes. Only 1 category can be chosen for scoring purposes. Use the default category meta box on the right side of the quiz editor to assign categories for display purposes. See the help guide for further details on the difference between "score" and "display" categorization.',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5885ddbbdb9a0',
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
			'key' => 'field_58850fe20fd70',
			'label' => 'Question Options',
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
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'key' => 'field_58851018ab8aa',
			'label' => 'Show points',
			'name' => 'qmqu_show_points',
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
			'key' => 'field_5885103d930b4',
			'label' => 'Number answers',
			'name' => 'qmqu_numbered_answer',
			'type' => 'true_false',
			'instructions' => 'If this option is activated, all answers are numbered (only single and multiple choice)',
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
			'key' => 'field_588c60b54aea6',
			'label' => 'Hide correct and incorrect messages',
			'name' => 'qmqu_hide_answer_message_box',
			'type' => 'true_false',
			'instructions' => 'If you enable this option, the correct or incorrect message will be displayed.',
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
			'key' => 'field_588c60ff4aea7',
			'label' => 'Correct and incorrect answer mark',
			'name' => 'qmqu_disabled_answer_mark',
			'type' => 'true_false',
			'instructions' => 'If you enable this option, answers won\'t be color highlighted as correct or incorrect.',
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
			'key' => 'field_588c617ea3acc',
			'label' => 'Force user to answer each question',
			'name' => 'qmqu_forcing_question_solve',
			'type' => 'true_false',
			'instructions' => 'If you enable this option, the user is forced to answer each question. If the option "Question overview" is activated, this notification will appear after end of the quiz, otherwise after each question.',
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
			'key' => 'field_588c61a5a3acd',
			'label' => 'Hide question position overview',
			'name' => 'qmqu_hide_question_position_overview',
			'type' => 'true_false',
			'instructions' => 'If you enable this option, the question position overview is hidden.',
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
			'key' => 'field_588c620a605b7',
			'label' => 'Hide question numbering',
			'name' => 'qmqu_hide_question_numbering',
			'type' => 'true_false',
			'instructions' => 'If you enable this option, the question numbering is hidden.',
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
			'key' => 'field_588c6226605b8',
			'label' => 'Display category',
			'name' => 'qmqu_show_category',
			'type' => 'true_false',
			'instructions' => 'If you enable this option, category will be displayed in the question.',
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
			'key' => 'field_5885c9428141a',
			'label' => 'Result Options',
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
			'default_value' => 0,
			'message' => 'If you enable this option, the results of each category is displayed on the results page.',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'key' => 'field_5885c97e8141c',
			'label' => 'Show category score',
			'name' => 'qmqu_show_category_score',
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
			'key' => 'field_5885dd64f48f5',
			'label' => 'Result Text',
			'name' => 'qmqu_result_text',
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
			'placement' => 'top',
			'endpoint' => 0,
			'key' => 'field_58a2f7d1c8834',
			'label' => 'Access',
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
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'key' => 'field_588c5ec91780d',
			'label' => 'Only registered users are allowed to start the quiz',
			'name' => 'qmqu_start_only_registered_user',
			'type' => 'true_false',
			'instructions' => 'If you enable this option, only registered users allowed start the quiz.',
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
			'key' => 'field_588c5cf0cb1af',
			'label' => 'Execute quiz only once',
			'name' => 'qmqu_quiz_run_once',
			'type' => 'true_false',
			'instructions' => 'If you activate this option, the user can complete the quiz only once. Afterwards the quiz is blocked for this user.',
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
			'key' => 'field_588a50b90d690',
			'label' => 'Questions',
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
					'post_type' => array (
						0 => 'quizmaster_question',
					),
					'taxonomy' => array (
					),
					'allow_null' => 0,
					'multiple' => 0,
					'return_format' => 'id',
					'ui' => 1,
					'key' => 'field_588a50ca0d692',
					'label' => 'Question',
					'name' => 'qmqu_question',
					'type' => 'post_object',
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
			'max' => 0,
			'layout' => 'table',
			'button_label' => '',
			'collapsed' => '',
			'key' => 'field_588a50ca0d691',
			'label' => 'Questions',
			'name' => 'qmqu_questions',
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
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'quizmaster_quiz',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => array (
		//0 => 'permalink',
		1 => 'the_content',
		2 => 'excerpt',
		3 => 'custom_fields',
		4 => 'discussion',
		5 => 'comments',
		6 => 'revisions',
		7 => 'author',
		8 => 'format',
		9 => 'page_attributes',
		10 => 'featured_image',
		11 => 'categories',
		12 => 'tags',
		13 => 'send-trackbacks',
	),
	'active' => 1,
	'description' => '',
);
