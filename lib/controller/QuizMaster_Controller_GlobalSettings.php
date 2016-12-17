<?php

class QuizMaster_Controller_GlobalSettings extends QuizMaster_Controller_Controller
{

    public function route()
    {
        $this->edit();
    }

    private function edit()
    {

        if (!current_user_can('quizMaster_change_settings')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $mapper = new QuizMaster_Model_GlobalSettingsMapper();
        $categoryMapper = new QuizMaster_Model_CategoryMapper();
        $templateMapper = new QuizMaster_Model_TemplateMapper();

        $view = new QuizMaster_View_GobalSettings();

        if (isset($this->_post['submit'])) {
            $mapper->save(new QuizMaster_Model_GlobalSettings($this->_post));
            QuizMaster_View_View::admin_notices(__('Settings saved', 'quizmaster'), 'info');

            $toplistDateFormat = $this->_post['toplist_date_format'];

            if ($toplistDateFormat == 'custom') {
                $toplistDateFormat = trim($this->_post['toplist_date_format_custom']);
            }

            $statisticTimeFormat = $this->_post['statisticTimeFormat'];

            if (add_option('quizMaster_toplistDataFormat', $toplistDateFormat) === false) {
                update_option('quizMaster_toplistDataFormat', $toplistDateFormat);
            }

            if (add_option('quizMaster_statisticTimeFormat', $statisticTimeFormat, '', 'no') === false) {
                update_option('quizMaster_statisticTimeFormat', $statisticTimeFormat);
            }
        } else {
            if (isset($this->_post['databaseFix'])) {
                QuizMaster_View_View::admin_notices(__('Database repaired', 'quizmaster'), 'info');

                $DbUpgradeHelper = new QuizMaster_Helper_DbUpgrade();
                $DbUpgradeHelper->databaseDelta();
            }
        }

        $view->settings = $mapper->fetchAll();
        $view->isRaw = !preg_match('[raw]', apply_filters('the_content', '[raw]a[/raw]'));
        $view->category = $categoryMapper->fetchAll();
        $view->categoryQuiz = $categoryMapper->fetchAll(QuizMaster_Model_Category::CATEGORY_TYPE_QUIZ);
        $view->email = $mapper->getEmailSettings();
        $view->userEmail = $mapper->getUserEmailSettings();
        $view->templateQuiz = $templateMapper->fetchAll(QuizMaster_Model_Template::TEMPLATE_TYPE_QUIZ, false);
        $view->templateQuestion = $templateMapper->fetchAll(QuizMaster_Model_Template::TEMPLATE_TYPE_QUESTION, false);

        $view->toplistDataFormat = get_option('quizMaster_toplistDataFormat', 'Y/m/d g:i A');
        $view->statisticTimeFormat = get_option('quizMaster_statisticTimeFormat', 'Y/m/d g:i A');

        $view->show();
    }
}