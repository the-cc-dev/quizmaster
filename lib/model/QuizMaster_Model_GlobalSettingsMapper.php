<?php

class QuizMaster_Model_GlobalSettingsMapper extends QuizMaster_Model_Mapper
{

    public function fetchAll()
    {
        $s = new QuizMaster_Model_GlobalSettings();

        $s->setAddRawShortcode(get_option('quizMaster_addRawShortcode'))
            ->setJsLoadInHead(get_option('quizMaster_jsLoadInHead'))
            ->setTouchLibraryDeactivate(get_option('quizMaster_touchLibraryDeactivate'))
            ->setCorsActivated(get_option('quizMaster_corsActivated'));

        return $s;
    }

    public function save(QuizMaster_Model_GlobalSettings $settings)
    {

        if (add_option('quizMaster_addRawShortcode', $settings->isAddRawShortcode()) === false) {
            update_option('quizMaster_addRawShortcode', $settings->isAddRawShortcode());
        }

        if (add_option('quizMaster_jsLoadInHead', $settings->isJsLoadInHead()) === false) {
            update_option('quizMaster_jsLoadInHead', $settings->isJsLoadInHead());
        }

        if (add_option('quizMaster_touchLibraryDeactivate', $settings->isTouchLibraryDeactivate()) === false) {
            update_option('quizMaster_touchLibraryDeactivate', $settings->isTouchLibraryDeactivate());
        }

        if (add_option('quizMaster_corsActivated', $settings->isCorsActivated()) === false) {
            update_option('quizMaster_corsActivated', $settings->isCorsActivated());
        }
    }

    public function delete()
    {
        delete_option('quizMaster_addRawShortcode');
        delete_option('quizMaster_jsLoadInHead');
        delete_option('quizMaster_touchLibraryDeactivate');
        delete_option('quizMaster_corsActivated');
    }

    /**
     * @return array
     */
    public function getEmailSettings()
    {
        $e = get_option('quizMaster_emailSettings', null);

        if ($e === null) {
            $e['to'] = '';
            $e['from'] = '';
            $e['subject'] = __('QuizMaster: One user completed a quiz', 'quizmaster');#
            $e['html'] = false;
            $e['message'] = __('QuizMaster

The user "$username" has completed "$quizname" the quiz.

Points: $points
Result: $result

', 'quizmaster');

        }

        return $e;
    }

    public function saveEmailSettiongs($data)
    {
        if (isset($data['html']) && $data['html']) {
            $data['html'] = true;
        } else {
            $data['html'] = false;
        }

        if (add_option('quizMaster_emailSettings', $data, '', 'no') === false) {
            update_option('quizMaster_emailSettings', $data);
        }
    }

    /**
     * @return array
     */
    public function getUserEmailSettings()
    {
        $e = get_option('quizMaster_userEmailSettings', null);

        if ($e === null) {
            $e['from'] = '';
            $e['subject'] = __('QuizMaster: One user completed a quiz', 'quizmaster');
            $e['html'] = false;
            $e['message'] = __('QuizMaster

You have completed the quiz "$quizname".

Points: $points
Result: $result

', 'quizmaster');

        }

        return $e;

    }

    public function saveUserEmailSettiongs($data)
    {
        if (isset($data['html']) && $data['html']) {
            $data['html'] = true;
        } else {
            $data['html'] = false;
        }

        if (add_option('quizMaster_userEmailSettings', $data, '', 'no') === false) {
            update_option('quizMaster_userEmailSettings', $data);
        }
    }
}