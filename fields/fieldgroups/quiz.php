<?php

$fieldGroup = array (
	'key' => 'quiz',
	'title' => 'QuizMaster Quiz',
	'fields' => array (
		array (
			'type' => 'tab',
			'placement' => 'left',
			'key' => 'qmqu_settings',
			'label' => 'Quiz',
		),
		array (
			'layout' => 'horizontal',
			'choices' => array (
				0 => 'Standard Flow',
				1 => 'Check & Continue',
				2 => 'Questions Stacked',
			),
			'return_format' => 'value',
			'key' => 'qmqu_quiz_modus',
			'label' => 'Quiz Mode',
			'type' => 'radio',
		),
		array (
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'label' => 'Hide quiz title',
			'key' => 'qmqu_title_hidden',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'default_value' => 0,
			'min' => '',
			'max' => '',
			'step' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => 'Seconds',
			'label' => 'Time Limit',
			'key' => 'qmqu_time_limit',
			'type' => 'number',
			'instructions' => 'Enter 0 for no time limit.',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'default_value' => 0,
			'message' => '',
			'label' => 'Randomize questions',
			'key' => 'qmqu_question_random',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'label' => 'Randomize answers',
			'key' => 'qmqu_answer_random',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'label' => __( 'Show only specific number of questions', 'quizmaster' ),
			'key' => 'qmqu_show_max_question',
			'type' => 'true_false',
			'instructions' => __( 'If you enable this option, maximum number of displayed questions will be X from X questions. (The output of questions is random)', 'quizmaster' ),
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'default_value' => 10,
			'min' => '',
			'max' => '',
			'step' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'label' => __( 'Maximum number of questions', 'quizmaster' ),
			'key' => 'qmqu_show_max_question_value',
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
		),
		array (
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'label' => 'Autostart',
			'key' => 'qmqu_autostart',
			'type' => 'true_false',
			'instructions' => 'If you enable this option, the quiz will start automatically after the page is loaded.',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'tabs' => 'all',
			'toolbar' => 'basic',
			'media_upload' => 1,
			'default_value' => '',
			'delay' => 0,
			'label' => 'Quiz Description',
			'key' => 'qmqu_quiz_description',
			'type' => 'wysiwyg',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'tabs' => 'all',
			'toolbar' => 'basic',
			'media_upload' => 1,
			'default_value' => '',
			'delay' => 0,
			'label' => 'Static Header Message',
			'key' => 'qmqu_static_header_message',
			'type' => 'wysiwyg',
			'instructions' => 'This message will appear above the quiz content and remain in place throughout the quiz.',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'placement' => 'left',
			'endpoint' => 0,
			'key' => 'qmqu_questions_tab',
			'label' => 'Questions',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'post_type' => array (
				0 => 'quizmaster_question',
			),
			'taxonomy' => array (
			),
			'allow_null' => 0,
			'multiple' => 1,
			'return_format' => 'id',
			'ui' => 1,
			'label' => 'Questions',
			'key' => 'qmqu_questions',
			'type' => 'relationship',
			'instructions' => 'Select questions on from the question pool on the left by clicking on them. Questions disabled from selection have already been added to the quiz. Questions will appear in the quiz in the order they appear in the selection box here, unless randomization settings are applied.',
			'required' => 0,
			'filters' => array (
				0 => 'search',
			),
			'selection_title' => 'Question Pool',
			'selected_title' => 'Selected Questions',
		),
		array (
			'default_value' => 0,
			'message' => '',
			'label' => 'Show points',
			'key' => 'qmqu_show_points',
			'type' => 'true_false',
		),
		array (
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'label' => 'Number answers',
			'key' => 'qmqu_numbered_answer',
			'type' => 'true_false',
			'instructions' => 'If this option is activated, all answers are numbered (only single and multiple choice)',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'label' => 'Hide correct and incorrect messages',
			'key' => 'qmqu_hide_answer_message_box',
			'type' => 'true_false',
			'instructions' => 'If you enable this option, the correct or incorrect message will not be displayed.',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'label' => 'Correct and incorrect answer mark',
			'key' => 'qmqu_disabled_answer_mark',
			'type' => 'true_false',
			'instructions' => 'If you enable this option, answers won\'t be color highlighted as correct or incorrect.',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'label' => 'Force user to answer each question',
			'key' => 'qmqu_forcing_question_solve',
			'type' => 'true_false',
			'instructions' => 'If you enable this option, the user is forced to answer each question. If the option "Question overview" is activated, this notification will appear after end of the quiz, otherwise after each question.',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'label' => 'Hide question position overview',
			'key' => 'qmqu_hide_question_position_overview',
			'type' => 'true_false',
			'instructions' => 'If you enable this option, the question position overview is hidden.',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'label' => 'Hide question numbering',
			'key' => 'qmqu_hide_question_numbering',
			'type' => 'true_false',
			'instructions' => 'If you enable this option, the question numbering is hidden.',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'label' => 'Display category',
			'key' => 'qmqu_show_category',
			'type' => 'true_false',
			'instructions' => 'If you enable this option, category will be displayed in the question.',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'placement' => 'left',
			'endpoint' => 0,
			'key' => 'quiz_results',
			'label' => 'Results',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'tabs' => 'all',
			'toolbar' => 'basic',
			'media_upload' => 1,
			'default_value' => '',
			'delay' => 0,
			'label' => 'Result Text',
			'key' => 'qmqu_result_text',
			'type' => 'wysiwyg',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'type' => 'tab',
			'placement' => 'left',
			'key' => 'quiz_navigation',
			'label' => 'Navigation',
		),
		array (
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'label' => 'Question overview',
			'key' => 'qmqu_show_review_question',
			'type' => 'true_false',
			'instructions' => 'Adds Question Overview box to the top of the quiz. Additional questions can be marked "to review". Additional quiz overview will be displayed, before quiz is completed.',
		),
		array (
			'default_value' => 0,
			'label' => 'Show skip button',
			'key' => 'qmqu_show_skip_button',
			'type' => 'true_false',
			'instructions' => 'Show skip button to allow quiz takers to skip the question.',
		),
		array (
			'default_value' => 0,
			'label' => 'Show back button',
			'key' => 'qmqu_show_back_button',
			'type' => 'true_false',
			'instructions' => 'Show back button to allow quiz takers to review previous questions.',
		),
		array (
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'label' => 'Hide restart quiz button',
			'key' => 'qmqu_btn_restart_quiz_hidden',
			'type' => 'true_false',
		),
		array (
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'label' => 'Hide view question button',
			'key' => 'qmqu_btn_view_question_hidden',
			'type' => 'true_false',
		),
		array (
			'placement' => 'left',
			'endpoint' => 0,
			'label' => 'Scores',
			'key' => 'qmqu_scores',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'default_value' => 1,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'label' => 'Activate score tracking',
			'key' => 'qmqu_statistics_on',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'default_value' => 0,
			'min' => '',
			'max' => '',
			'step' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => 'minutes',
			'label' => 'Score IP lock',
			'key' => 'qmqu_statistics_ip_lock',
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
			'label' => 'Score category',
			'key' => 'qmqu_score_category',
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
		),
		array (
			'default_value' => 0,
			'message' => 'If you enable this option, the results of each category is displayed on the results page.',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'label' => 'Show category score',
			'key' => 'qmqu_show_category_score',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'placement' => 'top',
			'endpoint' => 0,
			'label' => 'Access',
			'key' => 'qmqu_access',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'label' => 'Only registered users are allowed to start the quiz',
			'key' => 'qmqu_start_only_registered_user',
			'type' => 'true_false',
			'instructions' => 'If you enable this option, only registered users allowed start the quiz.',
			'required' => 0,
			'conditional_logic' => 0,
		),
		array (
			'default_value' => 0,
			'message' => '',
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'label' => 'Execute quiz only once',
			'key' => 'qmqu_quiz_run_once',
			'type' => 'true_false',
			'instructions' => 'If you activate this option, the user can complete the quiz only once. Afterwards the quiz is blocked for this user.',
			'required' => 0,
			'conditional_logic' => 0,
		),
	),
);
