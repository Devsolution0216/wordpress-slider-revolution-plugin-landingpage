=== WP Retina 2x ===
Contributors: TigrouMeow
Tags: retina, images, image, responsive, lazysizes, lazy, attachment, media, files, iphone, ipad, high-dpi
Donate link: https://commerce.coinbase.com/checkout/d047546a-77a8-41c8-9ea9-4a950f61832f
Requires at least: 4.4
Tested up to: 5.3
Requires PHP: 7.0
Stable tag: 5.6.0

Make your website look beautiful and crisp on modern displays by creating and displaying retina images. WP 4.4+ is also supported and enhanced.

== Description ==

This plugin does two things: it creates the image files required by the High-DPI devices and it displays them to your visitors accordingly. Your website will look beautiful and crisp on every device! The retina images will be generated for you automatically (though you can also do it manually) and served to the retina devices. The concept of retina images for full-size images is also a very unique feature which is only provided by this plugin (which is why it became unique). More information and tutorial available on the [official website](https://meowapps.com/plugin/wp-retina-2x/).

=== Retina Methods ===
It supports different methods to serve the images to your visitors. Why? Depending on the theme and plugins you are using (and also the way you use images in your website), not all the methods can work. Ideally, I would recommend using the Responsive Images method, then if it doesn't work, the PictureFill method (which covers normally most cases). Retina.js should be the fallback method. There are more methods than this with their specific options.

=== Plug & Play ===
With a well-made theme and website, this plugin doesn't require normally any specific set-up. All the defaults settings are fine. Works with multi-site and many kinds of installs.

=== Responsive Images ===
WP has support for Responsive Images and this plugin handles this nicely by adding the retina images in the src-set created by WordPress. The HTML for the images not handled by WP 4.4 (outside posts) will also be handled by the plugin (pick "Picturefill" method for this). The plugin also provides an option to disable the 'Medium Large' created by WordPress which is actually not useful (it's a hack basically), this plugin does it better.

=== Optimized ===
The plugin is very fast and optimized. It doesn't create anything in the database.

=== Pro Version ===
The Pro version adds support for Retina for full-size, support for lazy-loading for your responsive images and various options. And it supports my work :)

=== CSS & Background Images ===
The plugin cannot inject CSS to handles the images added through CSS, that's both too dangerous and potentially very incorrect. However, in its Pro version and with PictureFill, you get an option to replace the inline CSS background image by their retina equivalent.

= Quickstart =

1. Set your option (for instance, you probably don't need retina images for every sizes set-up in your WP).
2. Generate the retina images (required only the first time, then images are generated automatically).
3. Check if it works! - if it doesn't, read the FAQ, the tutorial, and check the forums.

== Changelog ==

= 5.6.0 =
* Add: Option to remove the image size threshold (which is set to 2560 since WordPress 5.3). 
* Updade: PHP Simple Dom updated to 1.9.1.

= 5.5.7 =
* Fix: Background CSS wasn't working properly in a few cases.
* Update: Lazysizes updated to 5.1.1 (from 5.0.0).
* Update: Parser optimized.

= 5.5.6 =
* Update: Lazysizes updated to 5.1.0 (from 4.0.4).

= 5.5.5 =
* Fix: Display Full-Size Retina uploader only if the option is active.

= 5.5.4 =
* Add: Filter for cropping plugins.

= 5.5.3 =
* Fix: Usage of Composer.
* Update: If available, will use the Full-Size Retina for generating Retina thumbnails.
* Fix: New version of HtmlDomParser.
* Update: New dashboard.

= 5.5.1 =
* Fix: Uploading a PNG as a Retina was turning its transparency into black.
* Fix: Now LazyLoad used with Keep SRC only loads one image, the right one (instead of two before). Thanks to Shane Bishop, the creator of EWWW (https://wordpress.org/plugins/ewww-image-optimizer/).

= 5.4.3 =
* Add: New hooks: wr2x_before_regenerate, wr2x_before_generate_thumbnails, wr2x_generate_thumbnails, wr2x_regenerate and wr2x_upload_retina.
* Fix: Issues where happening with a few themes (actually the pagebuilder they use) after the last update.
* Update: Lazysizes 4.0.4.

= 5.4.1 =
* Fix: Issues where happening with a few themes (actually the pagebuilder they use) after the last update.
* Update: Lazysizes 4.0.4.

= 5.4.0 =
* Update: Removed annoying message that could appear by mistake in the admin.
* Add: Direct upload of Retina for Full-Size (for Pro).

= 5.2.9 =
* Add: New option to Regenerate Thumbnails.
* Fix: Tiny CSS fix, and update fix.
* Important: A few options will be removed in the near future. Have a look at this: https://wordpress.org/support/topic/simplifying-wp-retina-2x-by-removing-options/.

= 5.2.8 =
* Fix: Security update.
* Update: Lazysizes 4.0.3.

= 5.2.6 =
* Fix: Avoid re-generating non-retina thumbnails when Generate is used.
* Fix: Use ___DIR___ to include plugin's files.
* Fix: Better explanation.

= 5.2.3 =
* Fix: Sanitization to avoid cross-site scripting.
* Fix: Additional security fixes.

= 5.2.0 =
* Fix: When metadata is broken, displays a message.
* Fix: A few icons weren't displayed nicely.
* Fix: When metadata is broken, displays a message.
* Update: From Lazysizes 3.0 to 4.0.1.
* Add: Option for forcing SSL Verify.

= 5.1.4 =
* Add: wr2x_retina_extension, wr2x_delete_attachment, wr2x_get_pathinfo_from_image_src, wr2x_picture_rewrite in the API.

= 5.0.5 =
* Fix: There was a issue with the .htaccess rewriting (Class ‘Meow_Admin’ not found).
* Update: Core was totally re-organized and cleaned. Ready for nice updates.
* Update: LazyLoading from version 2.0 to 3.0.
* Info: There will be an important warning showing up during this update. It is an important annoucement.

= 4.8.0 =
* Add: Retina Image Quality for JPG (between 0 and 100). I know this little setting was really wanted :)
* Fix: Disabled sizes weren't really disabled in the UI.
* Fix: Notices about Ignore appearing in other screens.
* Add: Handles incompatibility with JetPack's Photon.
* Info: If you are using Lightroom, please have a look at my plugin for synchronizing your Lightroom to WordPress: https://meowapps.com/wplr-sync/. And if youi love my Retina plugin, please write a little review here: https://wordpress.org/support/plugin/wp-retina-2x/reviews/?rate=5#new-post. Thank you :)

= 4.7.7 =
* Add: The Generate button (and the bulk Generate) will now also Re-Generate the thumbnails as well (like the Renerate Thumbnails plugin). If you are interested in a option to disable this behavior, please say so in the WP forums.

= 4.7.6 =
* Fix: Issue with Pro being non-Pro outside of WP Admin.
* Fix: Retina debugging file was not being created properly.

= 4.7.5 =
* Fix: Don't delete the full-size Retina if we re-generate.
* Fix: Little issue with Ignore.
* Update: Additional debugging.
* Info: Please write a review for the plugin if you are happy with it. I am trying my best to make this plugin to work with every kind of WP install and system :)

= 4.7.4 =
* Update: Retina was moved into a new Meow Apps menu. The whole Meow Apps menu can be then hidden. For a nicer WP admin. The whole admin UI was updated.
* Add: New PictureFill option: inline CSS background can be now replaced by Retina images (excellent for sliders for example).
* Add: Over HTTP Check option: check for retina image remotely, for example if you are using images from a different website or server, it will check for the Retina version. Works with the PictureFill method.
* Change: Mobile detection was completely turned off as I don't think it should be used, but let's see if some of yours still need it. Ideally I would like to remove it from the code.
* Fix: Check if the CDN is already present before modifying/adding.

= 4.6.0 =
* Fix: Button Details was not working properly.
* Fix: Removed the beta Retina Uploader which is not working yet (was included by mistake).
* Update: Added the info screen available in the Retina Dashboard in the Media Library as well and improved the UI a tiny bit (it was a bit messy if you had a lot of image sizes.)

= 4.5.8 =
* Update: LazyLoad 2.0.3
* Fix: Don't display Retina information for a media that is not an image.
* Update: Retina.js 2.0.0
* Fix: Drag & Drop upload was a bit buggy, it now has been improved a lot!
* Add: Option to hide the ads, flatter and message about the Pro.
* Update: Options styles.

= 4.4.6 =
* Update: LazyLoad 1.5
* Update: Retina.js 1.4
* Update: PictureFill JS 3.0.2
* Fix: LazyLoad was not playing well when WordPress creates the src-set by itself.
* Fix: Get the right max-upload size when using HHVM.
* Fix: Displays an error in the dashboard when the server-side fails to process uploads.
* Update: During bulk, doesn't stop in case of errors anymore but display an errors counter.
* Update: Ignore Responsive Images support if the media ID is not existent (in case of broken HTML).

= 4.4.0 =
* Info: Please read my blog post about WP 4.4 + Retina on https://meowapps.com/wordpress-4-4-retina/.
* Add: New "Responsive Images" method.
* Add: Lot more information is available in the Retina settings, to help the newbies :)
* Update: Headers are compliant to WP 4.4.
* Update: Dashboard has been revamped for Pro users. Standard users can still use Bulk functions.
* Update: Support for WP 4.4.

= 3.5.2 =
* Fix: Search string not null but empty induces error.
* Change: User Agent used for Pro authentication.
* Fix: Issues with class containing trailing spaces. Fixed in in SimpleHTMLDOM.
* Fix: Used to show weird numbers when using 9999 as width or height.
* Add: Filter and default filter to avoid certain IMG SRC to be checked/parsed by the plugin while rendering.

= 3.4.2 =
* Fix: Full-Size Retina wasn't removed when the original file was deleted from WP.
* Fix: Images set up with a 0x0 size must be skipped.
* Fix: There was an issue if the class starts with a space (broken HTML), plugin automatically fix it on the fly.
* Fix: Full-Size image had the wrong path in the Details screen.
* Fix: Option Auto Generate was wrongly show unchecked even though it is active by default.
* Update: Moved the filters to allow developers to use files hosted on another server.
* Update: Translation strings. If you want to translate the plugin in your language, please contact me :)

= 3.3.6 =
* Fix: There was an issue with local path for a few installs.
* Add: Introduced $wr2x_extra_debug for extra developer debug (might be handy).
* Fix: Issues with retina images outside the uploads directory.
* Add: Custom CDN Domain support (check the "Custom CDN Domain" option).
* Fix: Removed a console.log that was forgotten ;)
* Change: different way of getting the temporary folder to write files (might help in a few cases).

= 3.1.0 =
* Add: Lazy-loading option for PictureFill (Pro).
* Fix: For the Pro users having the IXR_client error.
* Fix: Plugin now works even behind a proxy.
* Fix: Little UI bug while uploading a new image.
* Add: In the dashboard, added tooltips showing the sizes of the little squares on hover.
* Fix: The plugin was not compatible with Polylang, now it works.

= 3.0.0 =
* Add: Link to logs from the dashboard (if logs are available), and possibility to clear it directly.
* Add: Replace the Full-Size directly by drag & drop in the box.
* Add: Support for WPML Media.
* Change: Picturefill script to 'v2.2.0 - 2014-02-03'.
* Change: Enhanced logs (in debug mode), much easier to read.
* Change: Dashboard enhanced, more clear, possibility of having many image sizes on the screen.
* Fix: Better handing of non-image media and image detection.
* Fix: Rounding issues always been present, they are now fixed with an 2px error margin.
* Fix: Warnings and issues in case of broken metadata and images.
* Add: (PRO) New pop-up screen with detailed information.
* Add: (PRO) Added Retina for Full-Size with upload feature. Please note that Full-Size Retina also works with the normal version but you will have to manually resize and upload them.
* Add: (PRO) Option to avoid removing img's src when using PictureFill.
* Info: The serial for the Pro version can be bought at https://meowapps.com/wp-retina-2x. Thanks for all your support, the plugin is going to be 3 years old this year! :)

= 2.6.0 =
* Add: Support Manual Image Crop, resize the @2x as the user manually cropped them (that's cool!).
* Change: Name will change little by little to WP Retina X and menus simplified to simply "Retina".
* Change: Simplification of the dashboard (more is coming).
* Change: PictureFill updated to 'v2.2.0 - 2014-12-19'.
* Fix: Issue with the upload directory on some installs.
* Info: Way more is coming soon to the dashboard, thanks for your patience :)
* Info: Manual Image Crop received a Pull Request from me to support the Retina cropping but it is not part of their current version yet (1.07). For a version of Manual Image Crop that includes this change, you can use my forked version: https://github.com/tigroumeow/wp-manual-image-crop.

= 1.6.0 =
* Add: HTML srcset method.

= 1.0.0 =
* Change: enhancement of the Retina Dashboard.
* Change: better management of the 'issues'.
* Change: handle images with technical problems.
* Fix: random little fixes again.
* Change: upload is now HTML5, by drag and drop in the Retina Dashboard!

= 0.9.4 =
* Fix: esthetical issue related to the icons in the Retina dashboard.
* Fix: warnings when uploading/replacing an image file.
* Change: Media Replace is not used anymore, the code has been embedded in the plugin directly.
* Update: to the new version of Retina.js (client-method).
* Fix: updated rewrite-rule (server-method) that works with multi-site.
* Fix: support for Network install (multi-site). Thanks to Jeremy (Retina-Images).

= 0.3.0 =
* Fix: was not generating the images properly on multisite WordPress installs.
* Add: warning message if using the server-side method without the pretty permalinks.
* Add: warning message if using the server-side method on a multisite WordPress install.
* Change: the client-method (retina.js) is now used by default.
* Fix: simplified version of the .htaccess directive.
* Fix: new version of the client-side method (Retina.js), works 100x faster.
* Fix: SQL optimization & memory usage huge improvement.

= 0.2.2 =
* Fix: the recommended resolution shown wasn't the most adequate one.
* Fix: in a few cases, the .htaccess wasn't properly generated.
* Fix: files were renamed to avoid conflicts.
* Add: paging for the Retina Dashboard.
* Add: 'Generate for all files' handles and shows if there are errors.
* Add: the Retina Dashboard.
* Add: can now generate Retina files in bulk.
* Fix: the cropped images were not 'cropped'.
* Add: The Retina Dashboard and the Media Library's column can be disabled via the settings.
* Fix: resolved more PHP warning and notices.

= 0.1 =
* Very first release.

== Installation ==

Quick and easy installation:

1. Upload the folder `wp-retina-2x` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Check the settings of WP Retina 2x in the WordPress administration screen.
4. Check the Retina Dashboard.
6. Read the tutorial about the plugin: <a href='https://meowapps.com/wp-retina-2x/tutorial/'>WP Retina 2x Tutorial</a>.

== Frequently Asked Questions ==

Users, you will find the FAQ here: https://meowapps.com/wp-retina-2x/faq/.

Developers, WP Retina 2x has a little API. Here are a few filters and actions you might want to use.

= Functions =
* wr2x_get_retina_from_url( $url ): return the URL of the retina image (empty string if not found)
* wr2x_get_retina( $syspath ): return the system path of the retina image (null if not found)

= Actions =
* wr2x_retina_file_added: called when a new retina file is created, 1st argument is $attachment_id (of the media) and second is the $retina_filepath
* wr2x_retina_file_removed: called when a new retina file is removed, 1st argument is $attachment_id (of the media) and second is the $retina_filepath

= Filters =
* wr2x_img_url: you can check and potentially override the $wr2x_img_url (normal/original image from the src) that will be used in the srcset for 1x
* wr2x_img_retina_url: you can check and potentially override the $wr2x_img_retina_url (retina image) that will be used in the srcset for 2x
* wr2x_img_src: you can check and potentially override the $wr2x_img_src that will be used in the img's src (only used in Pro version)
* wr2x_validate_src: the img src is passed; return it if it is valid, return null if it should be skipped

== Upgrade Notice ==

None.

== Screenshots ==

1. Retina Dashboard
2. Basic Settings
3. Advanced Settings
