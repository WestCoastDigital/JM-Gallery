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

class jmGallerySettings
{

	private $screen = JM_POST_TYPES;

	private $meta_fields = array(
		array(
			'label' => 'Image Thumbnail Size',
			'id' => 'jmgallery_thumbnails',
			'default' => 'medium',
			'type' => 'select',
			'options' => [],
		),
		array(
			'label' => 'Desktop Columns',
			'id' => 'jmgallery_desktop_columns',
			'default' => '3',
			'type' => 'number',
		),
		array(
			'label' => 'Tablet Columns',
			'id' => 'jmgallery_tablet_columns',
			'default' => '2',
			'type' => 'number',
		),
		array(
			'label' => 'Phone Columns',
			'id' => 'jmgallery_phone_columns',
			'default' => '1',
			'type' => 'number',
		),
		array(
			'label' => 'Shortcode',
			'id' => 'jmgallery_shortcode',
			'type' => 'readonly',
		),
	);

	public function __construct()
	{
		add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
		add_action('save_post', array($this, 'save_fields'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend'));
	}

	public function populate_thumbnail_sizes()
	{
		// Populate options for 'Image Thumbnail Size' select field using jm_image_sizes() function
		$this->meta_fields[0]['options'] = jm_image_sizes();
	}

	public function add_meta_boxes()
	{
		foreach ($this->screen as $single_screen) {
			add_meta_box(
				'settings',
				__('Settings', 'textdomain'),
				array($this, 'meta_box_callback'),
				$single_screen,
				'side',
				'default'
			);
		}
		$this->populate_thumbnail_sizes();
	}

	public function meta_box_callback($post)
	{
		wp_nonce_field('settings_data', 'settings_nonce');
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
				case 'select':
					$input = sprintf(
						'<select id="%s" name="%s">',
						$meta_field['id'],
						$meta_field['id']
					);
					foreach ($meta_field['options'] as $key => $value) {
						$meta_field_value = !is_numeric($key) ? $key : $value;
						$input .= sprintf(
							'<option %s value="%s">%s</option>',
							$meta_value === $meta_field_value ? 'selected' : '',
							$meta_field_value,
							$value
						);
					}
					$input .= '</select>';
					break;
				case 'readonly':
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s" readonly>',
						$meta_field['type'] !== 'color' ? 'style="width: 100%"' : '',
						$meta_field['id'],
						$meta_field['id'],
						$meta_field['type'],
						$meta_value
					);
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
		return '<tr><th>' . $label . '</th><td>' . $input . '</td></tr>';
	}

	public function save_fields($post_id)
	{
		if (!isset($_POST['settings_nonce']))
			return $post_id;
		$nonce = $_POST['settings_nonce'];
		if (!wp_verify_nonce($nonce, 'settings_data'))
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


		$desktop_columns = get_post_meta($post_id, 'jmgallery_desktop_columns', true) ?? 3;
		$tablet_columns = get_post_meta($post_id, 'jmgallery_tablet_columns', true) ?? 2;
		$mobile_columns = get_post_meta($post_id, 'jmgallery_phone_columns', true) ?? 1;

		// populate shortcode
		$shortcode = '[jmgallery id="' . $post_id . '" desktop="' . $desktop_columns . '" tablet="' . $tablet_columns . '" mobile="' . $mobile_columns . '"]';
		update_post_meta($post_id, 'jmgallery_shortcode', esc_attr($shortcode));
	}

	public function enqueue_frontend()
	{
		wp_enqueue_style('jm-gallery', JM_GALLERY_PLUGIN_URL . 'assets/css/gallery-frontend.css', array(), '1.0.0', 'all');
		wp_enqueue_script('jm-gallery', JM_GALLERY_PLUGIN_URL . 'assets/js/gallery-frontend.js', array(), '1.0.0', true);
	}
}

if (class_exists('jmGallerySettings')) {
	new jmGallerySettings;
};

function jm_image_sizes()
{
	$choices = [];
	$sizes = get_intermediate_image_sizes();
	$sizes[] = 'full';
	if (is_array($sizes) && count($sizes) > 0) {
		foreach ($sizes as $size) {
			$size = str_replace('_', ' ', $size);
			$size = str_replace('-', ' ', $size);
			$choices[$size] = ucwords($size);
		}
	}
	return $choices;
}
