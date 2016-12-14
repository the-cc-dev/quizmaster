<?php

class QuizMaster_Plugin_BpAchievementsV2
{

    public function __construct()
    {
        add_filter('dpa_get_addedit_action_descriptions_category_name', array($this, 'setCategoryName'), 10, 2);

        add_action('quizmaster_completed_quiz', array($this, 'quizFinished'));
    }

    public function setCategoryName($category_name, $category)
    {
        $other = 'Other';

        if (__($other, 'dpa') == $category_name && 'QuizMaster' == $category) {
            return 'QuizMaster';
        } else {
            return $category_name;
        }
    }

    public function quizFinished()
    {
        do_action('quizmaster_quiz_finished');
    }

    public static function install()
    {
        global $wpdb;

        if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}achievements_actions'") === null) {
            return false;
        }

        $actions = array(
            array(
                'category' => 'QuizMaster',
                'name' => 'quizmaster_quiz_finished',
                'description' => __('The user completed a quiz.', 'quizmaster')
            )
        );

        foreach ($actions as $action) {
            if ($wpdb->get_var("SELECT id FROM {$wpdb->prefix}achievements_actions WHERE name = 'quizmaster_quiz_finished'") === null) {
                $wpdb->insert($wpdb->prefix . 'achievements_actions', $action);
            }
        }

        return true;
    }

    public static function deinstall()
    {
        global $wpdb;

        if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}achievements_actions'") === null) {
            return false;
        }

        return $wpdb->delete($wpdb->prefix . 'achievements_actions', array('name' => 'quizmaster_quiz_finished'));
    }
}

function dpa_handle_action_quizmaster_quiz_finished()
{
    $func_get_args = func_get_args();

    if (function_exists('dpa_handle_action')) {
        dpa_handle_action('quizmaster_quiz_finished', $func_get_args);
    }
}