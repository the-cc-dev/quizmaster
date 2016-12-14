<?php

class QuizMaster_Plugin_BpAchievementsV3 extends DPA_Extension
{

    public function __construct()
    {
        $this->actions = array(
            'quizmaster_completed_quiz' => __('The user completed a quiz.', 'quizmaster'),
            'quizmaster_completed_quiz_100_percent' => __('The user completed a quiz with 100 percent.', 'quizmaster')
        );

        $this->contributors = array(
            array(
                'name' => 'Julius Fischer',
                'gravatar_url' => 'http://gravatar.com/avatar/c3736cd18c273f32569726c93f76244d',
                'profile_url' => 'http://profiles.wordpress.org/goldhat',
            )
        );

        $this->description = __('A powerful and beautiful quiz plugin for WordPress.', 'quizmaster');
        $this->id = 'quizmaster';
        $this->image_url = QUIZMASTER_URL . '/img/quizmaster.jpg';
        $this->name = __('QuizMaster', 'quizmaster');
        //$this->rss_url         = '';
        $this->small_image_url = QUIZMASTER_URL . '/img/quizmaster_small.jpg';
        $this->version = 5;
        $this->wporg_url = 'http://wordpress.org/extend/plugins/quizmaster/';
    }

    public function do_update()
    {
        $this->insertTerm();
    }

    public function insertTerm()
    {
        if (function_exists('dpa_get_event_tax_id')) {
            $taxId = dpa_get_event_tax_id();

            foreach ($this->actions as $actionName => $desc) {
                $e = term_exists($actionName, $taxId);

                if ($e === 0 || $e === null) {
                    wp_insert_term($actionName, $taxId, array('description' => $desc));
                }
            }

        }

    }
}