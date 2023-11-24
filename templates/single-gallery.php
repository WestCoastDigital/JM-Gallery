<?php get_header(); ?>

<div class="container">
    <h1><?= get_the_title(); ?></h1>

    <div class="description">
        <?= get_post_meta(get_the_ID(), 'jmgallery_description', true); ?>
    </div>

    <?php
    $gallery_images = get_post_meta(get_the_ID(), 'jmgallery_id', true);
    $desktop_columns = get_post_meta(get_the_ID(), 'jmgallery_desktop_columns', true);
    $tablet_columns = get_post_meta(get_the_ID(), 'jmgallery_tablet_columns', true);
    $mobile_columns = get_post_meta(get_the_ID(), 'jmgallery_phone_columns', true);
    if ($gallery_images) :
    ?>
        <div class="gallery jm-gallery" id="gallery-<?= get_the_ID() ?>">
            <?php foreach ($gallery_images as $gallery_image) : ?>
                <?php
                $size = get_post_meta(get_the_ID(), 'jmgallery_thumbnails', true);
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
                ?>
                <div class="<?= $class ?>">
                    <img src="<?= $image[0] ?>" loading="lazy" width="<?= $width ?>" height="<?= $height ?>" alt="<?= $image_alt ?>" />
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <style>
        .jm-gallery {
            grid-template-columns: repeat(<?php echo $mobile_columns; ?>, 1fr);
        }

        @media screen and (min-width: 768px) {
            .jm-gallery {
                grid-template-columns: repeat(<?php echo $tablet_columns; ?>, 1fr);
            }
        }

        @media screen and (min-width: 1024px) {
            .jm-gallery {
                grid-template-columns: repeat(<?php echo $desktop_columns; ?>, 1fr);
            }
        }
    </style>

    <?php get_footer(); ?>