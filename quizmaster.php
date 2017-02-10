<?php
/*
Plugin Name: QuizMaster
Plugin URI: http://wordpress.org/extend/plugins/quizmaster
Description: Best free quiz plugin for WordPress.
Version: 0.0.2
Author: Joel Milne, GoldHat Group
Author URI: https://goldhat.ca
Text Domain: quizmaster
Domain Path: /languages
*/

define('QUIZMASTER_VERSION', '0.37');
define('QUIZMASTER_DEV', true);
define('QUIZMASTER_PATH', dirname(__FILE__));
define('QUIZMASTER_URL', plugins_url('', __FILE__));
define('QUIZMASTER_FILE', __FILE__);
define('QUIZMASTER_PPATH', dirname(plugin_basename(__FILE__)));
define('QUIZMASTER_PLUGIN_PATH', QUIZMASTER_PATH . '/plugin');

$uploadDir = wp_upload_dir();

define('QUIZMASTER_CAPTCHA_DIR', $uploadDir['basedir'] . '/quizmaster_captcha');
define('QUIZMASTER_CAPTCHA_URL', $uploadDir['baseurl'] . '/quizmaster_captcha');

spl_autoload_register('quizMaster_autoload');

delete_option('quizMaster_dbVersion');

register_activation_hook(__FILE__, array('QuizMaster_Helper_Upgrade', 'upgrade'));
register_activation_hook( __FILE__, 'createDefaultEmails' );
register_activation_hook( __FILE__, 'createStudentReportPage' );

add_action('plugins_loaded', 'quizMaster_pluginLoaded');
add_action('init', 'quizmasterAddPostTypes');

if (is_admin()) {
  new QuizMaster_Controller_Admin();
} else {
  new QuizMaster_Controller_Front();
}

function quizMaster_autoload($class)
{
    $c = explode('_', $class);

    if ($c === false || count($c) != 3 || $c[0] !== 'QuizMaster') {
        return;
    }

    switch ($c[1]) {
        case 'View':
            $dir = 'view';
            break;
        case 'Model':
            $dir = 'model';
            break;
        case 'Helper':
            $dir = 'helper';
            break;
        case 'Controller':
            $dir = 'controller';
            break;
        case 'Plugin':
            $dir = 'plugin';
            break;
        default:
            return;
    }

    $classPath = QUIZMASTER_PATH . '/lib/' . $dir . '/' . $class . '.php';

    if (file_exists($classPath)) {
        /** @noinspection PhpIncludeInspection */
        include_once $classPath;
    }
}

function quizMaster_pluginLoaded() {

    load_plugin_textdomain('quizmaster', false, QUIZMASTER_PPATH . '/languages');

    if (get_option('quizMaster_version') !== QUIZMASTER_VERSION) {
        QuizMaster_Helper_Upgrade::upgrade();
    }


}

/**
 * Get template.
 *
 * Search for the template and include the file.
 *
 * @since 1.0.0
 *
 * @see wcpt_locate_template()
 *
 * @param string 	$template_name			Template to load.
 * @param array 	$args					Args passed for the template file.
 * @param string 	$string $template_path	Path to templates.
 * @param string	$default_path			Default path to template files.
 */
function quizmaster_parse_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {
  ob_start();
  quizmaster_get_template( $template_name, $args, $template_path, $default_path );
  $contents = ob_get_contents();
  ob_end_clean();
  return $contents;
}

/**
 * Get template.
 *
 * Search for the template and include the file.
 *
 * @since 1.0.0
 *
 * @see wcpt_locate_template()
 *
 * @param string 	$template_name			Template to load.
 * @param array 	$args					Args passed for the template file.
 * @param string 	$string $template_path	Path to templates.
 * @param string	$default_path			Default path to template files.
 */
function quizmaster_get_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {
	if ( is_array( $args ) && isset( $args ) ) :
		extract( $args );
	endif;
	$template_file = quizmaster_locate_template( $template_name, $tempate_path, $default_path );

  if ( ! file_exists( $template_file ) ) :
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
		return;
	endif;

	include $template_file;
}

/**
 * Locate template.
 *
 * Locate the called template.
 * Search Order:
 * 1. /themes/theme/quizmaster/$template_name
 * 2. /themes/theme/$template_name
 * 3. /plugins/quizmaster/templates/$template_name.
 *
 * @since 1.0.0
 *
 * @param 	string 	$template_name			Template to load.
 * @param 	string 	$string $template_path	Path to templates.
 * @param 	string	$default_path			Default path to template files.
 * @return 	string 							Path to the template file.
 */
function quizmaster_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	// Set variable to search in woocommerce-plugin-templates folder of theme.
	if ( ! $template_path ) :
		$template_path = 'quizmaster/';
	endif;
	// Set default plugin templates path.
	if ( ! $default_path ) :
		$default_path = plugin_dir_path( __FILE__ ) . 'templates/'; // Path to the template folder
	endif;
	// Search template file in theme folder.
	$template = locate_template( array(
		$template_path . $template_name,
		$template_name
	) );
	// Get plugins template file.
	if ( ! $template ) :
		$template = $default_path . $template_name;
	endif;

	return apply_filters( 'quizmaster_locate_template', $template, $template_name, $template_path, $default_path );
}

function quizmasterAddPostTypes() {
  register_post_type( 'quizmaster_quiz',
    array(
      'labels' => array(
        'name' => __( 'Quizzes' ),
        'singular_name' => __( 'Quiz' )
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'quiz'),
      'show_in_menu' => 'quizMaster',
      'supports' => array('title', 'revisions'),
    )
  );

  register_post_type( 'quizmaster_question',
    array(
      'labels' => array(
        'name' => __( 'Questions' ),
        'singular_name' => __( 'Question' )
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'question'),
      'show_in_menu' => 'quizMaster',
      'supports' => array('title', 'revisions'),
    )
  );

  register_post_type( 'quizmaster_email',
    array(
      'labels' => array(
        'name' => __( 'Emails' ),
        'singular_name' => __( 'Email' )
      ),
      'public' => true,
      'has_archive' => true,
      'show_in_menu' => 'quizMaster'
    )
  );

  register_post_type( 'quizmaster_score',
    array(
      'labels' => array(
        'name' => __( 'Quiz Scores' ),
        'singular_name' => __( 'Quiz Score' )
      ),
      'public' => true,
      'has_archive' => true,
      'show_in_menu' => 'quizMaster'
    )
  );

}

add_action( 'init', 'quizmasterRegisterTaxonomies' );

function quizmasterRegisterTaxonomies() {
	register_taxonomy(
		'quizmaster_quiz_category',
		'quizmaster_quiz',
		array(
			'label' => __( 'Quiz Category' ),
			'rewrite' => array( 'slug' => 'quiz-category' ),
			'hierarchical' => true,
		)
	);
  register_taxonomy(
		'quizmaster_quiz_tag',
		'quizmaster_quiz',
		array(
			'label' => __( 'Quiz Tag' ),
			'rewrite' => array( 'slug' => 'quiz-tag' ),
			'hierarchical' => false,
		)
	);
  register_taxonomy(
		'quizmaster_question_category',
		'quizmaster_question',
		array(
			'label' => __( 'Question Category' ),
			'rewrite' => array( 'slug' => 'question-category' ),
			'hierarchical' => true,
		)
	);
  register_taxonomy(
		'quizmaster_question_tag',
		'quizmaster_question',
		array(
			'label' => __( 'Question Tag' ),
			'rewrite' => array( 'slug' => 'question-tag' ),
			'hierarchical' => false,
		)
	);
}

/* ACF Integration */

add_filter('acf/settings/path', 'quizmasterAcfSettingsPath');
function quizmasterAcfSettingsPath( $path ) {
  return QUIZMASTER_PATH . '/acf/advanced-custom-fields-pro/';
}

add_filter('acf/settings/dir', 'quizmasterAcfSettingsDir');
function quizmasterAcfSettingsDir( $dir ) {
  return QUIZMASTER_URL . '/acf/advanced-custom-fields-pro/';
}

include_once( QUIZMASTER_PATH . '/acf/advanced-custom-fields-pro/acf.php' );

if( !QUIZMASTER_DEV ) {
  include_once( QUIZMASTER_PATH . '/acf/fieldgroups/quizmaster_fieldgroups.php' );
}

/* Options Pages */
$option_page = acf_add_options_page(array(
		'page_title' 	=> 'QuizMaster Settings',
		'menu_title' 	=> 'Settings',
		'menu_slug' 	=> 'quizmaster-settings',
    'parent_slug' => 'quizMaster',
 		'capability' 	=> 'edit_posts',
	));

/* Single Quiz Template */
add_filter('single_template', 'quizmaster_quiz_template');

function quizmaster_quiz_template($single) {
  global $post;
  if ($post->post_type == "quizmaster_quiz") {
    return quizmaster_locate_template( 'quiz.php' );
  }
  if ($post->post_type == "quizmaster_score") {
    return quizmaster_locate_template( 'score.php' );
  }
  return $single;
}

/* Single Question Template */
add_filter('single_template', 'quizmaster_question_template');

function quizmaster_question_template($single) {
  global $post;
  if ($post->post_type == "quizmaster_question") {
    return quizmaster_locate_template( 'question.php' );
  }
  return $single;
}

/* Statistics Link in Quiz Table */
add_filter('post_row_actions','statisticsRow', 10, 2);
function statisticsRow($actions, $post){
    //check for your post type
    if ($post->post_type =="quizmaster_quiz"){
      $statsUrl = admin_url('admin.php?page=quizMaster&module=statistics&id=' . $post->ID);
      $actions['statistics'] = '<a href="'. $statsUrl .'">Statistics</a>';
    }
    return $actions;
}

/* Activate Revisions for Quizzes & Questions */
add_filter( 'wp_revisions_to_keep', 'filter_function_name', 10, 2 );
function filter_function_name( $num, $post ) {
  if( $post->post_type == 'quizmaster_quiz' || $post->post_type == 'quizmaster_question' ) {
    return -1;
  }
  return $num;
}

/* Quiz Score Columns */
add_filter('manage_quizmaster_score_posts_columns', 'quizmaster_score_columns');
function quizmaster_score_columns( $columns ) {
  $columns['date'] = 'Taken At';
  return array_merge($columns,
    array(
      'quiz'    => 'Quiz',
      'user'    => 'User',
      'points'  => 'Points',
      'correct' => 'Correct'
    )
  );
}

add_filter('manage_quizmaster_score_posts_custom_column', 'quizmaster_score_column_content', 10, 2);
function quizmaster_score_column_content( $column, $post_id ) {

  $score = new QuizMaster_Model_Score( $post_id );

  switch ( $column ) {
    case 'quiz' :
      $quizId = get_field( 'qm_score_quiz', $post_id );
      print get_the_title( $quizId );
      break;
    case 'user' :
      $user = get_field( 'qm_score_user', $post_id );
      print $user['display_name'];
      break;
    case 'points' :
      $totals = $score->getTotals();
      print $totals['pointsEarned'];
      break;
    case 'correct' :
      $totals = $score->getTotals();
      print $totals['qCorrect'] . '/' . $totals['qCount'];
      break;
  }
}

add_filter('manage_edit-quizmaster_score_sortable_columns', 'quizmaster_score_sortable_column');
function quizmaster_score_sortable_column( $columns ) {
  $columns['quiz']   = 'quiz';
  $columns['user']   = 'user';
  $columns['points'] = 'points';
  return $columns;
}

/* Quiz Scores Filters */
add_action( 'restrict_manage_posts', 'quizmaster_score_filter_quiz' );
function quizmaster_score_filter_quiz() {
  $type = 'post';
  if (isset($_GET['post_type'])) {
    $type = $_GET['post_type'];
  }

  //only add filter to post type you want
  if( 'quizmaster_score' == $type ) {

    // load quizzes
    $quizMapper = new QuizMaster_Model_QuizMapper;
    $quizzes = $quizMapper->fetchAll();
    if( !$quizzes ) {
      return;
    }

    // make values array
    $values = array();
    foreach( $quizzes as $quiz ) {
      $values[ $quiz->getId() ] = get_the_title( $quiz->getId() );
    }

    // selected quiz
    $selectedQuiz = isset($_GET['quiz'])? $_GET['quiz']:'';

    // filter select
    quizmaster_get_template('/reports/score-filter.php', array(
      'selectName' => 'qm_quiz',
      'values' => $values,
      'defaultLabel' => 'All quizzes',
      'selected' => $selectedQuiz,
    ));


    $users = get_users( 'orderby=nicename' );

    $values = array();
    foreach( $users as $user ) {
      $values[ $user->ID ] = $user->data->user_nicename;
    }

    // selected user
    $selectedUser = isset($_GET['user'])? $_GET['user']:'';

    // filter select
    quizmaster_get_template('/reports/score-filter.php', array(
      'selectName' => 'qm_user',
      'values' => $values,
      'defaultLabel' => 'All users',
      'selected' => $selectedUser,
    ));

  }
}

add_action( 'pre_get_posts', 'quizmaster_posts_filter', 10, 1 );
function quizmaster_posts_filter( $query ){

  global $pagenow;
  if( $pagenow != 'edit.php' ) {
    return $query;
  }

  if( !$query->is_main_query() ) return;
  if( !is_admin() ) return;

  $type = 'post';
  if (isset($_GET['post_type'])) {
    $type = $_GET['post_type'];
  }

  $metaQuery = $query->get('meta_query');

  if ( 'quizmaster_score' == $type && isset($_GET['qm_quiz']) && $_GET['qm_quiz'] != 0) {
    $metaQuery[] = array(
      'key'       => 'qm_score_quiz',
      'value'     => $_GET['qm_quiz'],
      'compare'   => '='
    );
  }

  if ( 'quizmaster_score' == $type && isset($_GET['qm_user']) && $_GET['qm_user'] != 0) {
    $metaQuery[] = array(
      'key'       => 'qm_score_user',
      'value'     => $_GET['qm_user'],
      'compare'   => '='
    );
  }

  $query->set('meta_query', $metaQuery);

}


/* Revision Cloning */
add_action( 'save_post', 'revisionTest', 50, 3 );
function revisionTest( $post_id, $post, $update ) {

  // exit if not quiz or question type
  $types = array('quizmaster_quiz', 'quizmaster_question');
  if( !in_array( $post->post_type, $types ) ) {
    return;
  }

  // Autosave, do nothing
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return;
  // AJAX? Not used here
  if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
    return;

  // exit if currently saving the revision
  if( false !== wp_is_post_revision( $post_id ) ) {
    return;
  }

  $revision_id = wp_save_post_revision( $post_id );

  if( !$revision_id ) {
    return;
  }

  acf_copy_postmeta( $post_id, $revision_id );

  return $post_id;

}

function generateRandomString($length = 10) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

function createDefaultEmails() {
  createDefaultEmailStudentCompletion();
}

function createDefaultEmailStudentCompletion() {
  $post = array(
    'post_type'     => 'quizmaster_email',
    'post_title'    => "Student Completion Email",
    'post_status'   => 'publish',
    'post_author'   => 1,
  );
  $post_id = wp_insert_post( $post );
  update_field('qm_email_key', 'student_completion', $post_id);
  update_field('qm_email_enabled', 1, $post_id);
  update_field('qm_email_trigger', 'completed_quiz', $post_id);
  update_field('qm_email_from', get_option('admin_email'), $post_id);
  update_field('qm_email_recipients', '[quiztaker_email]', $post_id);
  update_field('qm_email_subject', 'You Completed a Quiz', $post_id);
  update_field('qm_email_type', 'html', $post_id);
}

function createStudentReportPage() {
  $post = array(
    'post_type'     => 'page',
    'post_title'    => "Student Report",
    'post_status'   => 'publish',
    'post_content'  => '[quizmaster_student_report]',
    'post_author'   => 1,
  );
  $post_id = wp_insert_post( $post );
  setStudentReportPageOption( $post_id );
}

function setStudentReportPageOption( $post_id ) {
  update_field( 'qm_student_report_page', $post_id, 'option' );
}

function getStudentReportPageOption() {
  return get_field('qm_student_report_page', 'option');
}
