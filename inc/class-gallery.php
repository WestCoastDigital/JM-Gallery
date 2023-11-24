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

class jmGalleryMeta
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'gallery_metabox_enqueue']);
        add_action('add_meta_boxes', [$this, 'add_gallery_metabox']);
        add_action('save_post', [$this, 'gallery_meta_save']);
        add_action('init', [$this, 'image_size']);
    }

    public function gallery_metabox_enqueue($hook)
    {
        if ('post.php' == $hook || 'post-new.php' == $hook) {
            wp_enqueue_media();
            wp_enqueue_script('gallery-metabox', JM_GALLERY_PLUGIN_URL . '/assets/js/gallery-metabox.js', ['jquery', 'jquery-ui-sortable']);
            wp_enqueue_style('gallery-metabox', JM_GALLERY_PLUGIN_URL . '/assets/css/gallery-metabox.css');
        }
    }

    public function add_gallery_metabox($post_type)
    {
        $types = JM_POST_TYPES;

        if (in_array($post_type, $types)) {
            add_meta_box(
                'gallery-metabox',
                'Gallery',
                [$this, 'gallery_meta_callback'],
                $post_type,
                'normal',
                'high'
            );
        }
    }

    public function gallery_meta_save($post_id)
    {
        if (!isset($_POST['gallery_meta_nonce']) || !wp_verify_nonce($_POST['gallery_meta_nonce'], basename(__FILE__))) {
            return $post_id;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        if (isset($_POST['jmgallery_id'])) {
            $gallery_images = array_map('absint', $_POST['jmgallery_id']);
            update_post_meta($post_id, 'jmgallery_id', $gallery_images);
        } else {
            delete_post_meta($post_id, 'jmgallery_id');
        }
    }

    public function gallery_meta_callback($post)
    {
        wp_nonce_field(basename(__FILE__), 'gallery_meta_nonce');
        $ids = get_post_meta($post->ID, 'jmgallery_id', true);
?>
        <table class="form-table">
            <tr>
                <td>
                    <a class="gallery-add button" href="#" data-uploader-title="Add image(s) to gallery" data-uploader-button-text="Add image(s)">Add image(s)</a>

                    <ul id="gallery-metabox-list">
                        <?php if ($ids) : foreach ($ids as $key => $value) : $image = wp_get_attachment_image_src($value); ?>

                                <li>
                                    <input type="hidden" name="jmgallery_id[<?php echo $key; ?>]" value="<?php echo $value; ?>">
                                    <img class="image-preview" src="<?php echo $image[0]; ?>">
                                    <a class="change-image button button-small" href="#" data-uploader-title="Change image" data-uploader-button-text="Change image">Change image</a><br>
                                    <small><a class="remove-image" href="#">Remove image</a></small>
                                </li>

                        <?php endforeach;
                        endif; ?>
                    </ul>

                </td>
            </tr>
        </table>
<?php }

    public function image_size()
    {
        // register image size
        add_image_size('gallery-thumbnail', 1600, 900, false);
    }
}

if (class_exists('jmGalleryMeta')) {
    new jmGalleryMeta;
};
