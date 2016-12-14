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

function quizMaster_pluginLoaded()
{

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
