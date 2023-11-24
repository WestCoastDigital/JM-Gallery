<?php
/*
 * @author    Jon Mather
 * @copyright Copyright (c) 2023, Jon Mather
 * @license   http://en.wikipedia.org/wiki/MIT_License The MIT License
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class jmGalleryCpt
{
    public function __construct()
    {
        add_action('init', array($this, 'create_post_type'));
        add_filter('single_template', [$this, 'load_custom_gallery_template']);
    }

    public function create_post_type()
    {
        $args = array(
            'labels' => array(
                'name' => 'Galleries',
                'singular_name' => 'Gallery',
            ),
            'menu_icon' => 'dashicons-format-gallery',
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'thumbnail'),
        );
        register_post_type('gallery', $args);

        // flush permalinks
        flush_rewrite_rules();
    }

    public function load_custom_gallery_template($template)
    {
        global $post;

        if ('gallery' === $post->post_type) {
            // Check if the theme has the template
            $theme_template = locate_template('single-gallery.php');

            if (!$theme_template) {
                // If the theme doesn't have the template, use the plugin's template
                $template = JM_GALLERY_PLUGIN_DIR . 'templates/single-gallery.php';
            }
        }
        return $template;
    }
}
if (class_exists('jmGalleryCpt')) {
    new jmGalleryCpt;
};
