=== Variation Swatches for WooCommerce ===
Contributors: themealien, coderexco
Tags: woocommerce, product attribute, product color, product size, variation swatches, variable products
Requires at least: 4.5
Tested up to: 5.3
Stable tag: 1.0.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

An extension of WooCommerce that make variable products be more beauty and friendly to customers.

== Description ==

Variation Swatches for WooCommerce plugin provides a much nicer way to display variations of variable products. This plugin will help you select style for each attribute as color, image or label. With this plugin, you can present product colors, sizes, styles and many things in a better way which is not supported by WooCommerce.
This plugin only adds more options to show product variations with swatches. It doesn't touch the default drop-down style of WooCommerce.

With a friendly and easy-to-use interface, you can add default color, image or label to each attribute in the attributes management page. It can also helps you pick the right style for quick-add attribute right inside the editing product page.

**Features provided with this plugin:**

* Completely integrate with WooCommerce plugin
* Work on variable product only
* Create attribute color swatches
* Create attribute image swatches
* Create attribute label/text swatches
* Manage attribute globally
* Create new attribute swatch in product editing page


== Installation ==

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser.

1. Log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.
1. In the search field type "Variation Swatches for WooCommerce" and click Search Plugins.
1. Once you’ve found it, you can install it by simply clicking Install Now button.

= Manual Installation =

1. Download the Variation Swatches for WooCommerce plugin to your desktop.
1. Extract the plugin folder to your desktop.
1. Read through the "readme" file thoroughly to ensure you follow the installation instructions.
1. With your FTP program, upload the Plugin folder to the wp-content/plugins folder in your WordPress directory online.
1. Go to Plugins screen and find the "Variation Swatches for WooCommerce" in the list.
1. Click Activate to activate it.

= Config attributes =

Even this plugin has been installed and activated on your site, variable products will still show dropdowns if you've not configured product attributes.

1. Log in to your WordPress dashboard, navigate to the Products menu and click Attributes.
1. Click to attribute name to edit an exists attribute or in the Add New Attribute form you will see the default Type selector.
1. Click to that Type selector to change attribute's type. Besides default options Select and Text, there are more 3 options Color, Image, Label to choose.
1. Select the suitable type for your attribute and click Save Change/Add attribute
1. Go back to manage attributes screen. Click the cog icon on the right side of attribute to start editing terms.
1. Start adding new terms or editing exists terms. There is will be a new option at the end of form that let you choose the color, upload image or type the label for those terms.


== Frequently Asked Questions ==

= Will this plugin work with my theme? =
Yes, it will work with any theme, but may require some styling to make it match nicely.

== Screenshots ==

1. Variations on frontend
1. Add new attribute
1. Edit Attribute
1. Attribute color
1. Attribute label
1. Attribute image
1. Add new attribute color when edit a product

== Changelog ==

= 1.0.6 =
* New - Add a notice in admin area to restore attribute types after re-activating the plugin.
* Tweak - Rename the tooltip CSS class following WooCommerce standard.
* Tweak - Remove opacity from swatches.
* Security - Using $wpdb->prepare to get attribute data.

= 1.0.5 =
* Add tooltip

= 1.0.4 =
* Fix the issue of product attributes are not saved.
* Restore attribute type to "Select" if the plugin is deactivated.

= 1.0.3 =
* Fix the issue of adding attributes in the "Attributes" tab

= 1.0.2 =
* Support WooCommerce 3.0

= 1.0.1 =
* Add "swatches-support" class to the variations form

= 1.0.0 =
* Initial release.