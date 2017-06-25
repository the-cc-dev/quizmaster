<?php

class QuizMaster_Controller_Admin {

    protected $_ajax;

    public function __construct() {

      // init ajax handlers
      $this->_ajax = new QuizMaster_Controller_Ajax();
      $this->_ajax->init();

      // add admin menu items
      add_action( 'admin_menu', array($this, 'addMenuItems'), 50 );
      add_action( 'admin_menu', array($this, 'menuItemSort'), 110 );

      // init controller email
      $emailCtr = new QuizMaster_Controller_Email();

    }

    private function localizeScript() {
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

				wp_enqueue_style( 'quizmaster-admin-css', plugins_url('css/quizmaster-admin.css', QUIZMASTER_FILE), array(), QUIZMASTER_VERSION );

        $this->localizeScript();
    }

    public function menuItemSort() {
      global $submenu;

      $qm_submenu = $submenu['quizmaster'];
      foreach( $qm_submenu as $i => $item ) {
        $pos = array_search( $item[2], $GLOBALS['quizmaster_menu'] );
        if( !$pos ) {
          $pos = 100; // default position
        }
        $item['pos'] = $pos;
        $qm_submenu[ $i ] = $item;
      }

      usort($qm_submenu, function($a, $b) {
        return $a['pos'] - $b['pos'];
      });

      $submenu['quizmaster'] = $qm_submenu;

    }

    public function addMenuItems() {
        $pages = array();

        $pages[] = add_menu_page(
          'QuizMaster',
          'QuizMaster',
          'quizmaster_show',
          'quizmaster',
          array($this, 'route'),
          'dashicons-welcome-learn-more'
        );

        do_action( 'quizmaster_add_menu_item', 'quizmaster-categories-tags' );
        $pages[] = QuizMaster_Helper_Submenu::add(
          'quizmaster',
          __('Categories', 'quizmaster'),
          __('Categories', 'quizmaster'),
          'quizmaster_manage_settings',
          'quizmaster-categories-tags',
          array($this, 'route'),
          50
        );

        do_action( 'quizmaster_add_menu_item', 'quizmaster-support' );
        $pages[] = QuizMaster_Helper_Submenu::add(
          'quizmaster',
          __('Help', 'quizmaster'),
          __('Help', 'quizmaster'),
          'quizmaster_manage_settings',
          'quizmaster-support',
          array($this, 'route'),
          60
        );

        foreach ($pages as $p) {

          add_action('load-' . $p, array($this, 'routeLoadAction'));
        }

				add_action('admin_print_scripts', array($this, 'enqueueScript'));
    }

    public function routeLoadAction() {

        // screen handling
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

    public function route(){
      $this->_route();
    }

    private function _route( $routeAction = false ) {

      $module = isset($_GET['module']) ? $_GET['module'] : 'overallView';

      if (isset($_GET['page'])) {
        if (preg_match('#quizmaster-(.+)#', trim($_GET['page']), $matches)) {
          $module = $matches[1];
        }
      }

      $c = null;

      switch ($module) {
        case 'support':
          $c = new QuizMaster_Controller_Support();
          break;
        case 'categories-tags':
          $c = new QuizMaster_Controller_Taxonomies();
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
