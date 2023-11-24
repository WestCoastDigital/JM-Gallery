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

#### Can I create my own template?
Yes! Just add a single-gallery.php to your theme/child theme and customise to your hearts content

#### Can I hide the title when using the shortcode?
Absolutely, just add title=false to your shortcode

#### The title for when showing with short code is wrapped in H2, can I change it?
Of course, just add title_tag='h3', change to what you want, in to your shortcode

#### Can I hide the description when using the shortcode?
Absolutely, just add description=false to your shortcode

#### How is it accessible?
We have made it accessible by adding support to:
1. open modal using enter key instead of clicking
1. set the tab focus on open
1. use arrows to scroll through images instead of clicking the navigation arrows
1. use the escape key and reset focus to close modal instead of clicking on the close

#### Is this theme or plugin dependant?
No. I built this to be a stand alone plugin. It does not rely on any plugins or frameworks to function as everything is created and coded by myself, including the gallery field.