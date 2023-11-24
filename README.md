# Gallery Plugin
A WordPress plugin to create and add galleries that open in modal that is accessible

## Usage
It adds a custom post type called galleries that has a description and gallery image field

### Create Gallery
![Create Gallery](https://github.com/WestCoastDigital/JM-Gallery/blob/main/assets/image/cpt.png?raw=true)

1. Give it a name
1. Add a description to show before the image gallery if you like
1. Add your images, holding down CMD or Alt allows you to select multiple images
1. Set your display size and the columns

### Displaying Gallery
![Display Gallery](https://github.com/WestCoastDigital/JM-Gallery/blob/main/assets/image/gallery.png?raw=true)

1. The plugin creates a single view template for the gallery
1. In the settings there is a shortcode field you can copy and display the gallery elsewhere

![Gallery Modal](https://github.com/WestCoastDigital/JM-Gallery/blob/main/assets/image/modal.png?raw=true)

## FAQs

### Can I create my own template?
Yes! Just add a single-gallery.php to your theme/child theme and customise to your hearts content

## Can I hide the title when using the shortcode?
Absolutely, just add title=false to your shortcode

## The title for when showing with short code is wrapped in H2, can I change it?
Of course, just add title_tag='h3', change to what you want, in to your shortcode

## Can I hide the description when using the shortcode?
Absolutely, just add description=false to your shortcode