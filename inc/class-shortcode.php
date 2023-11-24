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

class jmGalleryShortcode
{

    public function __construct()
    {
        add_shortcode('jmgallery', [$this, 'gallery_shortcode']);
    }

    public function gallery_shortcode($atts)
    {
        $atts = shortcode_atts(
            [
                'id' => '',
                'desktop' => '3',
                'tablet' => '2',
                'mobile' => '1',
                'title' => true,
                'title_tag' => 'h2',
                'description' => true,
            ],
            $atts,
            'jmgallery'
        );

        if (empty($atts['id'])) {
            return;
        }
        $id = $atts['id'];
        $desktop_columns = $atts['desktop'] ?? '';
        $content = '';
        if ($desktop_columns == '') {
            $desktop_columns = '3';
        }
        $tablet_columns = $atts['tablet'] ?? '';
        if ($tablet_columns == '') {
            $tablet_columns = '2';
        }
        $mobile_columns = $atts['mobile'] ?? '';
        if ($mobile_columns == '') {
            $mobile_columns = '1';
        }
        $gallery_images = get_post_meta($id, 'jmgallery_id', true);
        if ($gallery_images) :
            if ($atts['title'] == true) :
                $title_tag = $atts['title_tag'] ?? 'h2';
                $content .= '<' . $title_tag . '>' . get_the_title($id) . '</' . $title_tag . '>';
            endif;
            if ($atts['description'] == true) :
                $content .= get_post_meta($id, 'jmgallery_description', true);
            endif;
            $content .= '<div class="gallery jm-gallery" id="gallery-' . $id . '">';
            foreach ($gallery_images as $gallery_image) :
                $size = get_post_meta($id, 'jmgallery_thumbnails', true);
                $image = wp_get_attachment_image_src($gallery_image, $size);
                $image_alt = get_post_meta($gallery_image, '_wp_attachment_image_alt', TRUE);
                $width = $image[1] ?? 1;
                $height = $image[2] ?? 1;
                $classes = [
                    'gallery-item' => 'gallery-item',
                    'vertical' => $height > $width ? 'vertical' : '',
                    'horizontal' => $width > $height ? 'horizontal' : '',
                ];
                $class = implode(' ', $classes);
                $content .= '<div class="' . $class . '">';
                $content .= '<img src="' . $image[0]  . '" loading="lazy" width="' . $width . '" height="' . $height . '" alt="' . $image_alt . '" />';
                $content .= '</div>';
            endforeach;
            $content .= '</div>';
        endif;
        $content .= '<style>';
        $content .= '#gallery-' . $id . ' {';
        $content .= 'grid-template-columns: repeat(' . $mobile_columns . ', 1fr);';
        $content .= '}';
        $content .= '@media screen and (min-width: 768px) {';
        $content .= '#gallery-' . $id . ' {';
        $content .= 'grid-template-columns: repeat(' . $tablet_columns . ', 1fr);';
        $content .= '}';
        $content .= '}';
        $content .= '@media screen and (min-width: 1024px) {';
        $content .= '#gallery-' . $id . ' {';
        $content .= 'grid-template-columns: repeat(' . $desktop_columns  . ', 1fr);';
        $content .= '}';
        $content .= '}';
        $content .= '</style>';

        return $content;
    }
}

if (class_exists('jmGalleryShortcode')) {
    new jmGalleryShortcode;
};
