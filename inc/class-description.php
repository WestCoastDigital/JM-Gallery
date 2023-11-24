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

class jmGalleryDescription
{

    private $screen = JM_POST_TYPES;

    private $meta_fields = [
        [
            'label' => 'Description',
            'id' => 'jmgallery_description',
            'type' => 'wysiwyg',
        ],
    ];

    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_fields']);
        // add_action('admin_head', [$this, 'remove_media_buttons']);
    }

    public function add_meta_boxes()
    {
        foreach ($this->screen as $single_screen) {
            add_meta_box(
                'description',
                __('Description', 'textdomain'),
                [$this, 'meta_box_callback'],
                $single_screen,
                'normal',
                'high'
            );
        }
    }

    public function meta_box_callback($post)
    {
        wp_nonce_field('description_data', 'description_nonce');
        $this->field_generator($post);
    }

    public function field_generator($post)
    {
        $output = '';
        foreach ($this->meta_fields as $meta_field) {
            $label = '<label for="' . $meta_field['id'] . '">' . $meta_field['label'] . '</label>';
            $meta_value = get_post_meta($post->ID, $meta_field['id'], true);
            if (empty($meta_value)) {
                if (isset($meta_field['default'])) {
                    $meta_value = $meta_field['default'];
                }
            }
            switch ($meta_field['type']) {
                case 'textarea':
                    $input = sprintf(
                        '<textarea style="width: 100%%" id="%s" name="%s" rows="5">%s</textarea>',
                        $meta_field['id'],
                        $meta_field['id'],
                        $meta_value
                    );
                    break;
                case 'wysiwyg':
                    ob_start();
                    wp_editor($meta_value, $meta_field['id']);
                    $input = ob_get_contents();
                    ob_end_clean();
                    break;
                default:
                    $input = sprintf(
                        '<input %s id="%s" name="%s" type="%s" value="%s">',
                        $meta_field['type'] !== 'color' ? 'style="width: 100%"' : '',
                        $meta_field['id'],
                        $meta_field['id'],
                        $meta_field['type'],
                        $meta_value
                    );
            }
            $output .= $this->format_rows($label, $input);
        }
        echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
    }

    public function format_rows($label, $input)
    {
        // return '<tr><th>'.$label.'</th><td>'.$input.'</td></tr>';
        return '<tr>' . $input . '</tr>';
    }

    public function save_fields($post_id)
    {
        if (!isset($_POST['description_nonce']))
            return $post_id;
        $nonce = $_POST['description_nonce'];
        if (!wp_verify_nonce($nonce, 'description_data'))
            return $post_id;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;
        foreach ($this->meta_fields as $meta_field) {
            if (isset($_POST[$meta_field['id']])) {
                switch ($meta_field['type']) {
                    case 'email':
                        $_POST[$meta_field['id']] = sanitize_email($_POST[$meta_field['id']]);
                        break;
                    case 'text':
                        $_POST[$meta_field['id']] = sanitize_text_field($_POST[$meta_field['id']]);
                        break;
                }
                update_post_meta($post_id, $meta_field['id'], $_POST[$meta_field['id']]);
            } else if ($meta_field['type'] === 'checkbox') {
                update_post_meta($post_id, $meta_field['id'], '0');
            }
        }
    }

    public function remove_media_buttons()
    {
        global $current_screen;
        $post_type = $current_screen->post_type;
        if (in_array($post_type, JM_POST_TYPES)) {
            remove_action('media_buttons', 'media_buttons');
        }
    }
}

if (class_exists('jmGalleryDescription')) {
    new jmGalleryDescription;
};
