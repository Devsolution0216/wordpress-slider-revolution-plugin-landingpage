=== Meta Box for Yoast SEO ===
Contributors: metabox, rilwis, fitwp, thaoha
Donate link: http://www.deluxeblogtips.com/donate
Tags: meta-box, custom-fields, custom-field, meta, meta-boxes, wordpress-seo, seo, seo-analysis, content-analysis, yoast, yoast-seo
Requires at least: 4.2
Tested up to: 4.9.7
Stable tag: 1.3.4
License: GPL-2.0

Add content of custom fields to Yoast SEO Content Analysis.

== Description ==

**Meta Box for WordPress SEO** is a free extension for [Meta Box](https://metabox.io) plugin which allows developers to add content of custom fields to Yoast SEO Content Analysis to have better SEO score.

There are situations when we create custom fields to store real content for the posts. They are actually displayed in the frontend. Search engines such as Google or Bing see them and analyze them. But by default, Yoast SEO plugin takes only post content to analyze for SEO score and gives us feedback based on the post content. The plugin doesn’t analyze the all the content that appears to the search engine, which is a big missing part and that sometimes confuses us in SEO term.

So, the plugin *Meta Box for Yoast SEO* fixes that problem by allowing us to add custom fields to the content analyzed by Yoast SEO plugin. This way, the Yoast SEO plugin and search engine will see the same content and we will have a correct advice for SEO content as well as correct SEO score.

### Plugin Links

- [Homepage](https://metabox.io/plugins/meta-box-yoast-seo/)
- [Github repo](https://github.com/rilwis/mb-yoast-seo)
- [View other premium extensions](https://metabox.io/plugins/)

== Installation ==

You need to install [**Meta Box**](https://metabox.io) first

1. Go to **Plugins | Add New** and search for **Meta Box**
1. Click **Install** to install the plugin

Install **Meta Box Yoast SEO extension**

1. Go to **Plugins | Add New** and search for **Meta Box Yoast SEO**
1. Click **Install** to install the plugin

To start using text limiter, just add the following parameters to fields:

`'add_to_wpseo_analysis' => true,`

== Frequently Asked Questions ==

== Screenshots ==

1. Plugin in action

== Changelog ==

= 1.3.4 =
* Added check to see if element exists before it's targeted.
* Fixed JS error when use group fields

= 1.3.3 =
* Use helper function to get correct URL to current folder, which can be used in plugins and themes

= 1.3.2 =
* Fix: Make sure JavaScript file is enqueued only for posts. No conflicts with MB Settings Page or other extensions.

= 1.3.1 =
* Improvement: Make the Yoast SEO analyzer works for existing content when page loads.

= 1.3 =
* Improvement: Works with wysiwyg (editor) field, including cloned fields.

= 1.2 =
* Improvement: Now the plugin works with cloned fields.

= 1.1.2 =
* Fix: Update to compatible with Yoast SEO 3.x

= 1.1.1 =
* Fix: Uncaught TypeError: Cannot set property 'textContent' of null
* Fix: Now works with Group extension

= 1.1.0 =
* Update to work with Yoast SEO 3.0 which allows you to live preview the result with Javascript.

= 1.0 =
* First release

== Upgrade Notice ==
