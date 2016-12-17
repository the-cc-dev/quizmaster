<?php

class QuizMaster_Controller_Admin
{

    protected $_ajax;

    public function __construct()
    {

        $this->_ajax = new QuizMaster_Controller_Ajax();
        $this->_ajax->init();

        add_action('admin_menu', array($this, 'register_page'));

        add_filter('set-screen-option', array($this, 'setScreenOption'), 10, 3);
    }

    public function setScreenOption($status, $option, $value)
    {
        if (in_array($option, array('quizmaster_quiz_overview_per_page', 'quizmaster_question_overview_per_page'))) {
            return $value;
        }

        return $status;
    }

    private function localizeScript()
    {
        global $wp_locale;

        $isRtl = isset($wp_locale->is_rtl) ? $wp_locale->is_rtl : false;

        $translation_array = array(
            'delete_msg' => __('Do you really want to delete the quiz/question?', 'quizmaster'),
            'no_title_msg' => __('Title is not filled!', 'quizmaster'),
            'no_question_msg' => __('No question deposited!', 'quizmaster'),
            'no_correct_msg' => __('Correct answer was not selected!', 'quizmaster'),
            'no_answer_msg' => __('No answer deposited!', 'quizmaster'),
            'no_quiz_start_msg' => __('No quiz description filled!', 'quizmaster'),
            'fail_grade_result' => __('The percent values in result text are incorrect.', 'quizmaster'),
            'no_nummber_points' => __('No number in the field "Points" or less than 1', 'quizmaster'),
            'no_nummber_points_new' => __('No number in the field "Points" or less than 0', 'quizmaster'),
            'no_selected_quiz' => __('No quiz selected', 'quizmaster'),
            'reset_statistics_msg' => __('Do you really want to reset the statistic?', 'quizmaster'),
            'no_data_available' => __('No data available', 'quizmaster'),
            'no_sort_element_criterion' => __('No sort element in the criterion', 'quizmaster'),
            'dif_points' => __('"Different points for every answer" is not possible at "Free" choice', 'quizmaster'),
            'category_no_name' => __('You must specify a name.', 'quizmaster'),
            'confirm_delete_entry' => __('This entry should really be deleted?', 'quizmaster'),
            'not_all_fields_completed' => __('Not all fields completed.', 'quizmaster'),
            'temploate_no_name' => __('You must specify a template name.', 'quizmaster'),
            'closeText' => __('Close', 'quizmaster'),
            'currentText' => __('Today', 'quizmaster'),
            'monthNames' => array_values($wp_locale->month),
            'monthNamesShort' => array_values($wp_locale->month_abbrev),
            'dayNames' => array_values($wp_locale->weekday),
            'dayNamesShort' => array_values($wp_locale->weekday_abbrev),
            'dayNamesMin' => array_values($wp_locale->weekday_initial),
//			'dateFormat'        => QuizMaster_Helper_Until::convertPHPDateFormatToJS(get_option('date_format', 'm/d/Y')),
            //e.g. "9 de setembro de 2014" -> change to "hard" dateformat
            'dateFormat' => 'mm/dd/yy',
            'firstDay' => get_option('start_of_week'),
            'isRTL' => $isRtl
        );

        wp_localize_script('quizMaster_admin_javascript', 'quizMasterLocalize', $translation_array);
    }

    public function enqueueScript()
    {
        wp_enqueue_script(
            'quizMaster_admin_javascript',
            plugins_url('js/quizMaster_admin' . (QUIZMASTER_DEV ? '' : '.min') . '.js', QUIZMASTER_FILE),
            array('jquery', 'jquery-ui-sortable', 'jquery-ui-datepicker'),
            QUIZMASTER_VERSION
        );

        wp_enqueue_style('jquery-ui',
            'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

        $this->localizeScript();
    }

    public function register_page()
    {
        $pages = array();

        $pages[] = add_menu_page(
          'QuizMaster',
          'QuizMaster',
          'quizMaster_show',
          'quizMaster',
          array($this, 'route'),
          'dashicons-welcome-learn-more'
        );

        $pages[] = add_submenu_page(
            'quizMaster',
            __('Global settings', 'quizmaster'),
            __('Global settings', 'quizmaster'),
            'quizMaster_change_settings',
            'quizMaster_glSettings',
            array($this, 'route'));

        $pages[] = add_submenu_page(
            'quizMaster',
            __('Help & Support', 'quizmaster'),
            __('Help & Support', 'quizmaster'),
            'quizMaster_show',
            'quizMaster_wpq_support',
            array($this, 'route'));

        foreach ($pages as $p) {
            add_action('admin_print_scripts-' . $p, array($this, 'enqueueScript'));
            add_action('load-' . $p, array($this, 'routeLoadAction'));
        }
    }

    public function routeLoadAction()
    {
        $screen = get_current_screen();

        if (!empty($screen)) {
            // Workaround for wp_ajax_hidden_columns() with sanitize_key()
            $name = strtolower($screen->id);

            if (!empty($_GET['module'])) {
                $name .= '_' . strtolower($_GET['module']);
            }

            set_current_screen($name);

            $screen = get_current_screen();
        }

        $helperView = new QuizMaster_View_GlobalHelperTabs();

        $screen->add_help_tab($helperView->getHelperTab());
        $screen->set_help_sidebar($helperView->getHelperSidebar());

        $this->_route(true);
    }

    public function route()
    {
        $this->_route();
    }

    private function _route($routeAction = false)
    {
        $module = isset($_GET['module']) ? $_GET['module'] : 'overallView';

        if (isset($_GET['page'])) {
            if (preg_match('#quizMaster_(.+)#', trim($_GET['page']), $matches)) {
                $module = $matches[1];
            }
        }

        $c = null;

        switch ($module) {
            case 'overallView':
                $c = new QuizMaster_Controller_Quiz();
                break;
            case 'question':
                $c = new QuizMaster_Controller_Question();
                break;
            case 'preview':
                $c = new QuizMaster_Controller_Preview();
                break;
            case 'statistics':
                $c = new QuizMaster_Controller_Statistics();
                break;
            case 'importExport':
                $c = new QuizMaster_Controller_ImportExport();
                break;
            case 'glSettings':
                $c = new QuizMaster_Controller_GlobalSettings();
                break;
            case 'styleManager':
                $c = new QuizMaster_Controller_StyleManager();
                break;
            case 'toplist':
                $c = new QuizMaster_Controller_Toplist();
                break;
            case 'wpq_support':
                $c = new QuizMaster_Controller_WpqSupport();
                break;
            case 'info_adaptation':
                $c = new QuizMaster_Controller_InfoAdaptation();
                break;
        }

        if ($c !== null) {
            if ($routeAction) {
                if (method_exists($c, 'routeAction')) {
                    $c->routeAction();
                }
            } else {
                $c->route();
            }
        }
    }
}
