<?php
/*
Plugin Name: Gallery Plugin
Description: Custom post type for galleries with image selection and description.
Version: 1.0
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('JM_GALLERY_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('JM_GALLERY_PLUGIN_URL', plugin_dir_url(__FILE__));
define('JM_POST_TYPES', ['gallery']);

// Include dependencies
require_once JM_GALLERY_PLUGIN_DIR . 'inc/class-cpt.php';
require_once JM_GALLERY_PLUGIN_DIR . 'inc/class-description.php';
require_once JM_GALLERY_PLUGIN_DIR . 'inc/class-gallery.php';
require_once JM_GALLERY_PLUGIN_DIR . 'inc/class-settings.php';
require_once JM_GALLERY_PLUGIN_DIR . 'inc/class-shortcode.php';
