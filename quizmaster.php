<?php
/*
Plugin Name: QuizMaster
Plugin URI: http://wordpress.org/extend/plugins/quizmaster
Description: Best free quiz plugin for WordPress.
Version: 1.0.0
Author: Joel Milne, GoldHat Group
Author URI: https://goldhat.ca
Text Domain: quizmaster
Domain Path: /languages
*/

define('QUIZMASTER_VERSION', '1.0.0');

define('QUIZMASTER_DEV', false);

define('QUIZMASTER_PATH', dirname(__FILE__));
define('QUIZMASTER_URL', plugins_url('', __FILE__));
define('QUIZMASTER_FILE', __FILE__);
define('QUIZMASTER_PPATH', dirname(plugin_basename(__FILE__)));
define('QUIZMASTER_PLUGIN_PATH', QUIZMASTER_PATH . '/plugin');

$uploadDir = wp_upload_dir();

define('QUIZMASTER_CAPTCHA_DIR', $uploadDir['basedir'] . '/quizmaster_captcha');
define('QUIZMASTER_CAPTCHA_URL', $uploadDir['baseurl'] . '/quizmaster_captcha');

spl_autoload_register('quizMaster_autoload');

register_activation_hook(__FILE__, array('QuizMaster_Helper_Upgrade', 'upgrade'));

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

function quizMaster_achievementsV3()
{
    if (function_exists('achievements')) {
        achievements()->extensions->quizmaster = new QuizMaster_Plugin_BpAchievementsV3();

        do_action('quizMaster_achievementsV3');
    }
}

add_action('dpa_ready', 'quizMaster_achievementsV3');

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
      'show_in_menu' => 'quizMaster'
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
