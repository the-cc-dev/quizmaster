<?php

class QuizMaster_Helper_Upgrade
{

    public static function upgrade()
    {

        QuizMaster_Helper_Upgrade::updateDb();

        $oldVersion = get_option('quizMaster_version');

        if ($oldVersion == '0.20') {
            QuizMaster_Helper_Upgrade::updateV21();
        }

        switch ($oldVersion) {
            case '0.17':
            case '0.18':
                QuizMaster_Helper_Upgrade::updateV19();
            case '0.19':
                QuizMaster_Helper_Upgrade::updateV20();
            case '0.20':
            case '0.21':
            case '0.22':
            case '0.23':
            case '0.24':
            case '0.25':
            case '0.26':
            case '0.27':
            case '0.28':
            case '0.29':
            case '0.30':
            case '0.31':
            case '0.32':
            case '0.33':
            case '0.34':
            case '0.35':
            case '0.36':
                break;
            default:
                QuizMaster_Helper_Upgrade::install();
                break;
        }

        if (add_option('quizMaster_version', QUIZMASTER_VERSION) === false) {
            update_option('quizMaster_version', QUIZMASTER_VERSION);
        }
    }

    private static function install()
    {
        $role = get_role('administrator');

        $role->add_cap('quizMaster_show');
        $role->add_cap('quizMaster_add_quiz');
        $role->add_cap('quizMaster_edit_quiz');
        $role->add_cap('quizMaster_delete_quiz');
        $role->add_cap('quizMaster_show_statistics');
        $role->add_cap('quizMaster_reset_statistics');
        $role->add_cap('quizMaster_import');
        $role->add_cap('quizMaster_export');
        $role->add_cap('quizMaster_change_settings');
        $role->add_cap('quizMaster_toplist_edit');

        //ACHIEVEMENTS Version 2.x.x
        if (defined('ACHIEVEMENTS_IS_INSTALLED') && ACHIEVEMENTS_IS_INSTALLED === 1 && defined('ACHIEVEMENTS_VERSION')) {
            $version = ACHIEVEMENTS_VERSION;
            if ($version{0} == '2') {
                QuizMaster_Plugin_BpAchievementsV2::install();
            }
        }
    }

    private static function updateV19()
    {
        $role = get_role('administrator');

        $role->add_cap('quizMaster_toplist_edit');
    }

    private static function updateDb()
    {
        $db = new QuizMaster_Helper_DbUpgrade();
        $v = $db->upgrade(get_option('quizMaster_dbVersion', false));

        if (add_option('quizMaster_dbVersion', $v) === false) {
            update_option('quizMaster_dbVersion', $v);
        }
    }

    private static function updateV20()
    {
        global $wpdb;

        $results = $wpdb->get_results("
			SELECT id, answer_data 
			FROM {$wpdb->prefix}wp_pro_quiz_question
			WHERE answer_type = 'cloze_answer' AND answer_points_activated = 1", ARRAY_A);

        foreach ($results as $row) {
            if (QuizMaster_Helper_Until::saveUnserialize($row['answer_data'], $into)) {
                $points = 0;

                foreach ($into as $c) {
                    preg_match_all('#\{(.*?)(?:\|(\d+))?(?:[\s]+)?\}#im', $c->getAnswer(), $matches);

                    foreach ($matches[2] as $match) {
                        if (empty($match)) {
                            $match = 1;
                        }

                        $points += $match;
                    }
                }

                $wpdb->update($wpdb->prefix . 'wp_pro_quiz_question', array('points' => $points),
                    array('id' => $row['id']));
            }
        }
    }

    private static function updateV21()
    {
        global $wpdb;

        $results = $wpdb->get_results("
				SELECT id, answer_data, answer_type, answer_points_activated, points
				FROM {$wpdb->prefix}wp_pro_quiz_question", ARRAY_A);

        foreach ($results as $row) {
            if ($row['points']) {
                continue;
            }

            if (QuizMaster_Helper_Until::saveUnserialize($row['answer_data'], $into)) {

                $points = 0;

                if ($row['answer_points_activated']) {
                    $dPoints = 0;

                    foreach ($into as $c) {
                        if ($row['answer_type'] == 'cloze_answer') {
                            preg_match_all('#\{(.*?)(?:\|(\d+))?(?:[\s]+)?\}#im', $c->getAnswer(), $matches);

                            foreach ($matches[2] as $match) {
                                if (empty($match)) {
                                    $match = 1;
                                }

                                $dPoints += $match;
                            }
                        } else {
                            $dPoints += $c->getPoints();
                        }
                    }

                    $points = $dPoints;
                } else {
                    $points = 1;
                }

                $wpdb->update($wpdb->prefix . 'wp_pro_quiz_question', array('points' => $points),
                    array('id' => $row['id']));
            }
        }
    }

    public static function deinstall()
    {

    }
}