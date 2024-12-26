Hello, As discussed, here's the email that was sent to you on July 29th.



Hello,

Thanks for uploading your plugin, we can begin with the review. We are a group of volunteers who help you identify common issues so that you can make your plugin more secure, compatible, reliable and compliant with the guidelines.

There are issues with your plugin code preventing it from being approved immediately. We have pended your submission in order to help you correct all issues so that it may be approved and published.

We ask you read this email in its entirety, address all listed issues, and reply to this email after uploading a corrected version of your code. Failure to do so will result in your review being delayed or even rejected.

We know this email can be long, but we kindly ask you to be meticulous in fixing the issues we mention so that we can make the best use of our volunteer time and get your plugin approved as soon as possible.

Remember that in addition to code quality, security and functionality, we require all plugins to adhere to our guidelines. If you have not yet, please read them: https://developer.wordpress.org/plugins/wordpress-org/detailed-plugin-guidelines/

Finally, should you at any time wish to alter your permalink (aka the plugin slug), you must explicitly tell us what you want it to be. Just changing the display name is not sufficient, and we require to you clearly state your desired permalink. Remember, permalinks cannot be altered after approval.

Be aware that you will not be able to submit another plugin while this one is being reviewed.

## Incorrect Stable Tag

In your readme, your 'Stable Tag' does not match the Plugin Version as indicated in your main plugin file.

ERROR: Stable tag shall not be trunk
customify-starter-sites.php - Version: 0.0.10

Your Stable Tag is meant to be the stable version of your plugin, not of WordPress. For your plugin to be properly downloaded from WordPress.org, those values need to be the same. If they're out of sync, your users won't get the right version of your code.

We recommend you use Semantic Versioning (aka SemVer) for managing versions:

https://en.wikipedia.org/wiki/Software_versioning
https://semver.org/

Please note: While currently using the stable tag of trunk currently works in the Plugin Directory, it's not actually a supported or recommended method to indicate new versions and has been known to cause issues with automatic updates.

We ask you please properly use tags and increment them when you release new versions of your plugin, just like you update the plugin version in the main file. Having them match is the best way to be fully forward supporting.

### ==> Fixed


## Declared license mismatched

When declaring the license for this plugin, it has to be the same.

Please make sure that you are declaring the same license in both the readme file and the plugin headers.

It is fine for this plugin to contain code from other sources under other licenses as long those are well documented and are under GPL or GPL-Compatible license, but we need a sole license declared for your code.

ERROR: License mismatched. Found "GPLv2 or later" in the readme (readme.txt) and "GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html" in the headers (customify-starter-sites.php)

### ==> Fixed

## Out of Date Libraries

At least one of the 3rd party libraries you're using is out of date. Please upgrade to the latest stable version for better support and security. We do not recommend you use beta releases.

From your plugin:

customify-starter-sites/assets/js/owl.carousel.js:1 🔴  Owl Carousel v2.3.3
   # ↳ Possible URL: https://github.com/OwlCarousel2/OwlCarousel2
customify-starter-sites/assets/js/owl.carousel.min.js:1 🔴  Owl Carousel v2.3.3
   # ↳ Possible URL: https://github.com/OwlCarousel2/OwlCarousel2
   
### ====================> Fixed

## Use wp_enqueue commands

Your plugin is not correctly including JS and/or CSS. You should be using the built in functions for this:

When including JavaScript code you can use:
wp_register_script() and wp_enqueue_script() to add JavaScript code from a file.
wp_add_inline_script() to add inline JavaScript code to previous declared scripts.


When including CSS you can use:
wp_register_style() and wp_enqueue_style() to add CSS from a file.
wp_add_inline_style() to add inline CSS to previously declared CSS.


Note that as of WordPress 6.3, you can easily pass attributes like defer or async: https://make.wordpress.org/core/2023/07/14/registering-scripts-with-async-and-defer-attributes-in-wordpress-6-3/

Also, as of WordPress 5.7, you can pass other attributes by using this functions and filters: https://make.wordpress.org/core/2021/02/23/introducing-script-attributes-related-functions-in-wordpress-5-7/

If you're trying to enqueue on the admin pages you'll want to use the admin enqueues.


https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
https://developer.wordpress.org/reference/hooks/admin_print_scripts/
https://developer.wordpress.org/reference/hooks/admin_print_styles/


Example(s) from your plugin:
customify-starter-sites/classess/class-tgm.php:3661 echo '<style type="text/css">#adminmenu .wp-submenu li.current { display: none !important; }</style>';
customify-starter-sites/classess/class-tgm.php:2618 echo '<style type="text/css">#adminmenu .wp-submenu li.current { display: none !important; }</style>';
customify-starter-sites/classess/class-tgm.php:912 echo '<style type="text/css">#adminmenu .wp-submenu li.current { display: none !important; }</style>';

========> Removed, FIXED

## Undocumented use of a 3rd Party or external service

We permit plugins to require the use of 3rd party (i.e. external) services, provided they are properly documented in a clear manner.

We require plugins that reach out to other services to disclose this, in clear and plain language, so users are aware of where data is being sent. This allows them to ensure that any legal issues with data transmissions are covered. This is true even if you are the 3rd party service.

In order to do so, you must update your readme to do the following:
Clearly explain that your plugin is relying on a 3rd party as a service and under what circumstances
Provide a link to the service .
Provide a link to the service terms of use and/or privacy policies.

Remember, this is for your own legal protection. Use of services must be upfront and well documented.

Example(s) from your plugin:
# Domain(s) not mentioned in the readme file.
customify-starter-sites/templates/dashboard.php:4 //Customify_Starter_Sites_Ajax::download_file('https://raw.githubusercontent.com/FameThemes/famethemes-xml-demos/master/boston/config.json');


customify-starter-sites/assets/js/owl.carousel.js:2349 url: '//vzaar.com/api/videos/' + video.id + '.json',
customify-starter-sites/importer/parsers/class-wxr-parser-simplexml.php:70 $namespaces['excerpt'] = 'http://wordpress.org/export/1.1/excerpt/';
customify-starter-sites/assets/js/owl.carousel.min.js:6 ...===f.type?c='<iframe width="'+g+'" height="'+h+'" src="//www.youtube.com/embed/'+f.id+"?autoplay=1&rel=0&v="+f.id+'" frameborder="0" allowfullscreen></iframe>':"vimeo"===f.type?c='<iframe src="//playe...  ...fullscreen></iframe>':"vimeo"===f.type?c='<iframe src="//player.vimeo.com/video/'+f.id+'?autoplay=1" width="'+g+'" height="'+h+'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscre...  ...llscreen mozallowfullscreen webkitAllowFullScreen src="//view.vzaar.com/'+f.id+'/player?autoplay=true"></iframe>'),a('<div>'+c+"</div>").insertAfter(e.find(".owl-video")),this....
customify-starter-sites/importer/parsers/class-wxr-parser-simplexml.php:67 $namespaces['wp'] = 'http://wordpress.org/export/1.1/';
customify-starter-sites/assets/js/owl.carousel.js:2338 url: '//vimeo.com/api/v2/video/' + video.id + '.json',
customify-starter-sites/assets/js/owl.carousel.js:2407 'src="//view.vzaar.com/' + video.id + '/player?autoplay=true"></iframe>';


## Including An Update Checker / Changing Updates functionality

Please remove the checks you have in your plugin to provide for updates.

We do not permit plugins to phone home to other servers for updates, as we are providing that service for you with WordPress.org hosting. One of our guidelines is that you actually use our hosting, so we need you to remove that code.

We also ask that plugins not interfere with the built-in updater as it will cause users to have unexpected results with WordPress 5.5 and up.

Example(s) from your plugin:

customify-starter-sites/classess/class-tgm.php:961 set_site_transient('update_plugins', $repo_updates);


## Changing Active Plugins

It is not allowed for plugins to change the activation status of other plugins, this is an action that must be performed by the user.

It is also not allowed to interfere with the user's actions when activating or deactivating a plugin, unless that's done to prevent errors (For example: When your plugin depends on another plugin, deactivate your own plugin when that other plugin is not active).

WordPress 6.5 introduces Plugin Dependencies, you can use it to manage dependencies (although it's fine if you use this as a fallback).

From your plugin:

customify-starter-sites/classess/class-tgm.php:877 new Plugin_Upgrader($skin);
customify-starter-sites/classess/class-tgm.php:880 add_filter('upgrader_source_selection', array($this, 'maybe_adjust_source_dir'), 1, 3);
customify-starter-sites/classess/class-tgm.php:1041 activate_plugin($file_path);
customify-starter-sites/classess/class-tgm.php:2046 deactivate_plugins($plugin['file_path']);
customify-starter-sites/classess/class-tgm.php:3467 activate_plugin($plugin_info);
customify-starter-sites/classess/class-tgm.php:3024 activate_plugins($plugins_to_activate);
customify-starter-sites/classess/class-tgm.php:2019 activate_plugin($plugin['file_path']);
customify-starter-sites/classess/class-plugin.php:23 activate_plugin($file_path, '', false, false);

... out of a total of 10 incidences.

## Internationalization: Text domain does not match plugin slug.

In order to make a string translatable in your plugin you are using a set of special functions. These functions collectively are known as "gettext".

These functions have a parameter called "text domain", which is a unique identifier for retrieving translated strings.

This "text domain" must be the same as your plugin slug so that the plugin can be translated by the community using the tools provided by the directory. As for example, if this plugin slug is penfold-macrame the Internationalization functions should look like:
esc_html__('Hello', 'penfold-macrame');

From your plugin, you have set your text domain as follows:
# This plugin is using the domain "tgmpa" for 4 element(s).

However, the current plugin slug is this:
customify-starter-sites


## Data Must be Sanitized, Escaped, and Validated

When you include POST/GET/REQUEST/FILE calls in your plugin, it's important to sanitize, validate, and escape them. The goal here is to prevent a user from accidentally sending trash data through the system, as well as protecting them from potential security issues.

SANITIZE: Data that is input (either by a user or automatically) must be sanitized as soon as possible. This lessens the possibility of XSS vulnerabilities and MITM attacks where posted data is subverted.

VALIDATE: All data should be validated, no matter what. Even when you sanitize, remember that you don’t want someone putting in ‘dog’ when the only valid values are numbers.

ESCAPE: Data that is output must be escaped properly when it is echo'd, so it can't hijack admin screens. There are many esc_*() functions you can use to make sure you don't show people the wrong data.

To help you with this, WordPress comes with a number of sanitization and escaping functions. You can read about those here:


https://developer.wordpress.org/apis/security/sanitizing/
https://developer.wordpress.org/apis/security/escaping/


Remember: You must use the most appropriate functions for the context. If you’re sanitizing email, use sanitize_email(), if you’re outputting HTML, use wp_kses_post(), and so on.

An easy mantra here is this:

Sanitize early
Escape Late
Always Validate

Clean everything, check everything, escape everything, and never trust the users to always have input sane data. After all, users come from all walks of life.

Example(s) from your plugin:
customify-starter-sites/classess/class-tgm.php:2904 $fields = array_keys( $_POST ); // Extra fields to pass to WP_Filesystem.
customify-starter-sites/classess/class-tgm.php:2855 $plugins_to_install = explode( ',', $_POST['plugin'] );
customify-starter-sites/classess/class-tgm.php:2852 $plugins_to_install = (array) $_POST['plugin'];
customify-starter-sites/classess/class-tgm.php:3000 $plugins = array_map( 'urldecode', (array) $_POST['plugin'] );
customify-starter-sites/classess/class-tgm.php:796 $slug = $this->sanitize_key( urldecode( $_GET['plugin'] ) );
customify-starter-sites/classess/class-ajax.php:320 $resources = isset( $_REQUEST['resources'] ) ? wp_unslash( $_REQUEST['resources'] ) : array();
 -----> wp_unslash($_REQUEST['resources'])


Note: There are simple ways to sanitize arrays, in case you need to do so, you can do the following:

An array of post IDs: array_unique(array_map('absint', $_POST['post_ids']))
An array of emails: array_map('sanitize_email', $_POST['user_emails'])
A multidimensional array, being all the elements texts: map_deep( $_POST['arrays_of_texts'], 'sanitize_text_field' )

Sometimes you'll have an array that contains different types of data inside, which would require different types of sanitization.

 $sanitized_orders = $_POST['orders']; // Sanitized below.
 array_walk_recursive( $sanitized_orders, 'prefix_sanitize_orders' );
 function prefix_sanitize_orders( &$item , $key ){
   switch ($key){
     case 'locator':
       $item = sanitize_key($item);
       break;
     case 'name':
       $item = sanitize_text_field($item);
       break;
     case 'price':
     case 'priceDiscounted':
       $item = (float)$item;
       break;
     default:
       $item = NULL;
   }
 } 

We have heuristically detected these cases of your plugin that might need array sanitization (might be false positives, please check them out):
customify-starter-sites/classess/class-tgm.php:3000 $plugins = array_map( 'urldecode', (array) $_POST['plugin'] );
customify-starter-sites/classess/class-tgm.php:2852 $plugins_to_install = (array) $_POST['plugin'];
customify-starter-sites/classess/class-tgm.php:2855 $plugins_to_install = explode( ',', $_POST['plugin'] );

... out of a total of 5 incidences.

## Processing the whole input

We strongly recommend you never attempt to process the whole $_POST/$_REQUEST/$_GET stack. This makes your plugin slower as you're needlessly cycling through data you don't need. Instead, you should only be attempting to process the items within that are required for your plugin to function.

Example(s) from your plugin:
customify-starter-sites/classess/class-tgm.php:3050 unset( $_POST ); // Reset the $_POST variable in case user wants to perform one action after another.
 -----> unset($_POST);
customify-starter-sites/classess/class-tgm.php:2904 $fields = array_keys( $_POST ); // Extra fields to pass to WP_Filesystem.


## Variables and options must be escaped when echo'd

Much related to sanitizing everything, all variables that are echoed need to be escaped when they're echoed, so it can't hijack users or (worse) admin screens. There are many esc_*() functions you can use to make sure you don't show people the wrong data, as well as some that will allow you to echo HTML safely.

At this time, we ask you escape all $-variables, options, and any sort of generated data when it is being echoed. That means you should not be escaping when you build a variable, but when you output it at the end. We call this 'escaping late.'

Besides protecting yourself from a possible XSS vulnerability, escaping late makes sure that you're keeping the future you safe. While today your code may be only outputted hardcoded content, that may not be true in the future. By taking the time to properly escape when you echo, you prevent a mistake in the future from becoming a critical security issue.

This remains true of options you've saved to the database. Even if you've properly sanitized when you saved, the tools for sanitizing and escaping aren't interchangeable. Sanitizing makes sure it's safe for processing and storing in the database. Escaping makes it safe to output.

Also keep in mind that sometimes a function is echoing when it should really be returning content instead. This is a common mistake when it comes to returning JSON encoded content. Very rarely is that actually something you should be echoing at all. Echoing is because it needs to be on the screen, read by a human. Returning (which is what you would do with an API) can be json encoded, though remember to sanitize when you save to that json object!

There are a number of options to secure all types of content (html, email, etc). Yes, even HTML needs to be properly escaped.

https://developer.wordpress.org/apis/security/escaping/

Remember: You must use the most appropriate functions for the context. There is pretty much an option for everything you could echo. Even echoing HTML safely.

Example(s) from your plugin:

customify-starter-sites/classess/class-export.php:498 <wp:tag_slug><?php echo wxr_cdata( $t->slug ); ?></wp:tag_slug>
customify-starter-sites/templates/modal.php:165 <a href="<?php echo home_url('/'); ?>" data-step="4" target="_blank"><?php _e('View Your Website', "customify-starter-sites", 'customify-sites'); // phpcs:ignore WordPress.Security.EscapeOutput.UnsafePrintingFunction ?></a>
 -----> echo home_url('/');
customify-starter-sites/classess/class-export.php:682 <wp:meta_key><?php echo wxr_cdata( $meta->meta_key ); ?></wp:meta_key>
customify-starter-sites/classess/class-export.php:611 <wp:post_name><?php echo wxr_cdata( $post->post_name ); ?></wp:post_name>
customify-starter-sites/classess/class-export.php:321 echo "\t\t<wp:term_description>" . wxr_cdata( $term->description ) . "</wp:term_description>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
customify-starter-sites/classess/class-export.php:506 <wp:term_id><?php echo wxr_cdata( $t->term_id ); ?></wp:term_id>
customify-starter-sites/classess/class-export.php:427 echo "\t\t<category domain=\"{$term->taxonomy}\" nicename=\"{$term->slug}\">" . wxr_cdata( $term->name ) . "</category>\n";
customify-starter-sites/templates/modal.php:133 </div><span><?php _e('Options', "customify-starter-sites", 'customify-sites'); // phpcs:ignore WordPress.Security.EscapeOutput.UnsafePrintingFunction ?></span>
 -----> _e('Options', "customify-starter-sites", 'customify-sites')

... out of a total of 103 incidences.

Note: The functions _e and _ex outputs the translation without escaping, please use an alternative function that escapes the output.
An alternative to _e would be esc_html_e , esc_attr_e or simply using __ wrapped by a escaping function and inside an echo .
An alternative to _ex would be using _x wrapped by a escaping function and inside an echo .

Examples:

<h2><?php esc_html_e('Settings page', 'plugin-slug'); ?></h2>
 <h2><?php echo esc_html(__('Settings page', 'plugin-slug')); ?></h2>
 <h2><?php echo esc_html(_x('Settings page', 'Settings page title', 'plugin-slug')); ?></h2> 

Example(s) from your plugin:
customify-starter-sites/templates/modal.php:20 <li data-step="import_options"><?php _e('Import Options', "customify-starter-sites", 'customify-sites'); // phpcs:ignore WordPress.Security.EscapeOutput.UnsafePrintingFunction ?></li>
 -----> _e('Import Options', "customify-starter-sites", 'customify-sites')
customify-starter-sites/templates/modal.php:117 <?php _e('This step will import the theme options, menus and widgets', "customify-starter-sites", 'customify-sites'); // phpcs:ignore WordPress.Security.EscapeOutput.UnsafePrintingFunction ?>
customify-starter-sites/templates/modal.php:150 <li><a target="_blank" href="https://wordpress.org/support/"><?php _e('Explore WordPress', "customify-starter-sites", 'customify-sites'); // phpcs:ignore WordPress.Security.EscapeOutput.UnsafePrintingFunction ?></a></li>
 -----> _e('Explore WordPress', "customify-starter-sites", 'customify-sites')
customify-starter-sites/templates/dashboard.php:12 <li><a href="#" data-slug="all"><?php _e( 'All', "customify-starter-sites", 'customify-sites' ); ?></a></li>
 -----> _e('All', "customify-starter-sites", 'customify-sites')

... out of a total of 38 incidences.

Note: The function __ retrieves the translation without escaping, please either:
Use an alternative function that escapes the resulting value such as esc_html__ or esc_attr__ .
Or wrap the __ function with a proper escaping function such as esc_html , esc_attr , wp_kses_post , etc.

Examples:

<h2><?php echo esc_html__('Settings page', 'plugin-slug'); ?></h2>
 <h2><?php echo esc_html(__('Settings page', 'plugin-slug')); ?></h2> 

Example(s) from your plugin:
customify-starter-sites/classess/class-sites.php:74 echo '<h1>'.__( 'Customify Site Library', "customify-starter-sites", 'customify-sites' ).'</h1><hr>';
 -----> __('Customify Site Library', "customify-starter-sites", 'customify-sites')
customify-starter-sites/importer/parsers/class-wxr-parser.php:46 echo __( 'Details are shown above. The importer will now try again with a different parser...', "customify-starter-sites", 'customify-sites' ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,WordPress.Security.EscapeOutput.OutputNotEscaped	
customify-starter-sites/importer/parsers/class-wxr-parser.php:45 echo '<p><strong>' . __( 'There was an error when reading this WXR file', "customify-starter-sites", 'customify-sites' ) . '</strong><br />'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,WordPress.Security.EscapeOutput.OutputNotEscaped	
 -----> __('There was an error when reading this WXR file', "customify-starter-sites", 'customify-sites')


Note: We know this is confusing, the esc_url_raw function is not an escaping function, but a sanitizing function similar to sanitize_url . Specifically it is used to sanitize a URL for use in a database or a redirection.

The appropriate function to escape a URL is esc_url .

Example(s) from your plugin:
customify-starter-sites/classess/class-export.php:654 <wp:comment_author_url><?php echo esc_url_raw( $c->comment_author_url ); ?></wp:comment_author_url>
 -----> esc_url_raw($c->comment_author_url)


## Generic function/class/define/namespace/option names

All plugins must have unique function names, namespaces, defines, class and option names. This prevents your plugin from conflicting with other plugins or themes. We need you to update your plugin to use more unique and distinct names.

A good way to do this is with a prefix. For example, if your plugin is called "Easy Custom Post Types" then you could use names like these:

function ecpt_save_post()
class ECPT_Admin{}
namespace ECPT;
update_option( 'ecpt_settings', $settings );
define( 'ECPT_LICENSE', true );
global $ecpt_options;


Don't try to use two (2) or three (3) letter prefixes anymore. We host nearly 100-thousand plugins on WordPress.org alone. There are tens of thousands more outside our servers. Believe us, you’re going to run into conflicts.

You also need to avoid the use of __ (double underscores), wp_ , or _ (single underscore) as a prefix. Those are reserved for WordPress itself. You can use them inside your classes, but not as stand-alone function.

Please remember, if you're using _n() or __() for translation, that's fine. We're only talking about functions you've created for your plugin, not the core functions from WordPress. In fact, those core features are why you need to not use those prefixes in your own plugin! You don't want to break WordPress for your users.

Related to this, using if (!function_exists('NAME')) { around all your functions and classes sounds like a great idea until you realize the fatal flaw. If something else has a function with the same name and their code loads first, your plugin will break. Using if-exists should be reserved for shared libraries only.

Remember: Good prefix names are unique and distinct to your plugin. This will help you and the next person in debugging, as well as prevent conflicts.

Analysis result:

# This plugin is using the prefix "customify" for 24 element(s).
# This plugin is using the prefix "tgmpa" for 20 element(s).
# This plugin is using the prefix "wxr" for 21 element(s).

# Cannot use "widget" as a prefix.
customify-starter-sites/classess/class-ajax.php:707 update_option('widget_' . $base_id, $single_widget_instances);
# Cannot use "load" as a prefix.
customify-starter-sites/classess/class-tgm.php:2095 function load_tgm_plugin_activation

# The prefix "wxr" is too short, we require prefixes at least over 4 characters.
customify-starter-sites/importer/parsers/class-wxr-parser-regex.php:12 class WXR_Parser_Regex
customify-starter-sites/importer/parsers/class-wxr-parser.php:12 class WXR_Parser
customify-starter-sites/importer/parsers/class-wxr-parser-simplexml.php:12 class WXR_Parser_SimpleXML
customify-starter-sites/importer/parsers/class-wxr-parser-xml.php:13 class WXR_Parser_XML
customify-starter-sites/importer/class-wxr-import-ui.php:188 apply_filters('wxr_importer.admin.import_options', $options);
customify-starter-sites/importer/class-wxr-importer.php:737 apply_filters('wxr_importer.pre_process.post', $data, $meta, $comments, $terms);
customify-starter-sites/importer/class-wxr-importer.php:776 do_action('wxr_importer.process_already_imported.post', $data);
customify-starter-sites/importer/class-wxr-importer.php:860 do_action('wxr_importer.process_skipped.post', $data, $meta);
customify-starter-sites/importer/class-wxr-importer.php:891 do_action('wxr_importer.process_failed.post', $post_id, $data, $meta, $comments, $terms);
customify-starter-sites/importer/class-wxr-importer.php:957 do_action('wxr_importer.processed.post', $post_id, $data, $meta, $comments, $terms);
customify-starter-sites/importer/class-wxr-importer.php:1153 apply_filters('wxr_importer.pre_process.post_meta', $meta_item, $post_id);
customify-starter-sites/importer/class-wxr-importer.php:1298 apply_filters('wxr_importer.pre_process.comment', $comment, $post_id);
customify-starter-sites/importer/class-wxr-importer.php:1318 do_action('wxr_importer.process_already_imported.comment', $comment);
customify-starter-sites/importer/class-wxr-importer.php:1394 do_action('wxr_importer.processed.comment', $comment_id, $comment, $meta, $post_id);
customify-starter-sites/importer/class-wxr-importer.php:1495 apply_filters('wxr_importer.pre_process.user', $data, $meta);
customify-starter-sites/importer/class-wxr-importer.php:1563 do_action('wxr_importer.process_failed.user', $user_id, $userdata);
customify-starter-sites/importer/class-wxr-importer.php:1589 do_action('wxr_importer.processed.user', $user_id, $userdata);
customify-starter-sites/importer/class-wxr-importer.php:1660 apply_filters('wxr_importer.pre_process.term', $data, $meta);
customify-starter-sites/importer/class-wxr-importer.php:1677 do_action('wxr_importer.process_already_imported.term', $data);
customify-starter-sites/importer/class-wxr-importer.php:1736 do_action('wxr_importer.process_failed.term', $result, $data, $meta);
customify-starter-sites/importer/class-wxr-importer.php:1764 do_action('wxr_importer.processed.term', $term_id, $data);

# Looks like there are elements not using common prefixes.
customify-starter-sites/importer/class-wxr-import-ui.php:201 apply_filters('import_allow_fetch_attachments', true);
customify-starter-sites/importer/class-wxr-import-ui.php:212 apply_filters('import_allow_create_users', true);
customify-starter-sites/importer/class-wxr-importer.php:529 do_action('import_start');
customify-starter-sites/importer/class-wxr-importer.php:556 do_action('import_end');
customify-starter-sites/importer/class-wxr-importer.php:846 apply_filters('wp_import_post_data_processed', $postdata, $data);
customify-starter-sites/importer/class-wxr-importer.php:871 do_action('wp_import_insert_post', $post_id, $original_id, $postdata, $data);
customify-starter-sites/importer/class-wxr-importer.php:919 apply_filters('wp_import_post_terms', $terms, $post_id, $data);
customify-starter-sites/importer/class-wxr-importer.php:937 do_action('wp_import_set_post_terms', $tt_ids, $ids, $tax, $post_id, $data);
customify-starter-sites/importer/class-wxr-importer.php:1158 apply_filters('import_post_meta_key', $meta_item['key'], $post_id, $post);
customify-starter-sites/importer/class-wxr-importer.php:1182 do_action('import_post_meta', $post_id, $key, $value);
customify-starter-sites/importer/class-wxr-importer.php:1281 apply_filters('wp_import_post_comments', $comments, $post_id, $post);
customify-starter-sites/importer/class-wxr-importer.php:1378 do_action('wp_import_insert_comment', $comment_id, $comment, $post_id, $post);
customify-starter-sites/importer/class-wxr-importer.php:1727 do_action('wp_import_insert_term_failed', $result, $data);
customify-starter-sites/importer/class-wxr-importer.php:1756 do_action('wp_import_insert_term', $term_id, $data);
customify-starter-sites/importer/class-wxr-importer.php:2106 apply_filters('import_attachment_size_limit', 0);
customify-starter-sites/classess/class-tgm.php:639 $body_id;
customify-starter-sites/classess/class-tgm.php:50 class TGM_Plugin_Activation
customify-starter-sites/classess/class-tgm.php:3111 class TGM_Bulk_Installer
customify-starter-sites/classess/class-tgm.php:3124 class TGM_Bulk_Installer_Skin


## Allowing Direct File Access to plugin files

Direct file access is when someone directly queries your file. This can be done by simply entering the complete path to the file in the URL bar of the browser but can also be done by doing a POST request directly to the file. For files that only contain a PHP class the risk of something funky happening when directly accessed is pretty small. For files that contain procedural code, functions and function calls, the chance of security risks is a lot bigger.

You can avoid this by putting this code at the top of all PHP files that could potentially execute code if accessed directly :

     if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

Example(s) from your plugin:

customify-starter-sites/templates/dashboard.php:12 
customify-starter-sites/templates/preview.php:12 
customify-starter-sites/templates/modal.php:4 
customify-starter-sites/customify-starter-sites.php:13 


## Unsafe SQL calls

When making database calls, it's highly important to protect your code from SQL injection vulnerabilities. You need to update your code to use wpdb calls and prepare() with your queries to protect them.

Please review the following:
https://developer.wordpress.org/reference/classes/wpdb/#protect-queries-against-sql-injection-attacks
https://codex.wordpress.org/Data_Validation#Database
https://make.wordpress.org/core/2012/12/12/php-warning-missing-argument-2-for-wpdb-prepare/
https://ottopress.com/2013/better-know-a-vulnerability-sql-injection/

Example(s) from your plugin:

customify-starter-sites/classess/class-export.php:371 $and = '';
customify-starter-sites/classess/class-export.php:369 $and = 'AND ID IN ( ' . implode( ', ', $post_ids ) . ')';
customify-starter-sites/classess/class-export.php:375 $results = $wpdb->get_results( "SELECT DISTINCT post_author FROM $wpdb->posts WHERE post_status != 'auto-draft' $and" );
# You cannot add calls like "implode(', ', $post_ids)" directly to the SQL query.
# Using wpdb::prepare($query, $args) you will need to include placeholders for each variable within the query and include the variables in the second parameter.
# The SQL query needs to be included in a wpdb::prepare($query, $args) function.


customify-starter-sites/classess/class-export.php:549 $where = 'WHERE ID IN (' . join( ',', $next_posts ) . ')';
customify-starter-sites/classess/class-export.php:550 $posts = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} $where" );
# You cannot add calls like "join(',', $next_posts)" directly to the SQL query.
# Using wpdb::prepare($query, $args) you will need to include placeholders for each variable within the query and include the variables in the second parameter.
# The SQL query needs to be included in a wpdb::prepare($query, $args) function.


Note: Passing individual values to wpdb::prepare using placeholders is fairly straightforward, but what if we need to pass an array of values instead?

You'll need to create a placeholder for each item of the array and pass all the corresponding values to those placeholders, this seems tricky, but here is a snippet to do so.

    


$wordcamp_id_placeholders = implode( ', ', array_fill( 0, count( $wordcamp_ids ), '%d' ) );        


$prepare_values = array_merge( array( $new_status ), $wordcamp_ids );        
     


$wpdb->query( $wpdb->prepare( "             UPDATE `$table_name`             SET `post_status` = %s             WHERE ID IN ( $wordcamp_id_placeholders )",             $prepare_values         ) );    
There is a core ticket that could make this easier in the future: https://core.trac.wordpress.org/ticket/54042

Example(s) from your plugin:
customify-starter-sites/classess/class-export.php:369 $and = 'AND ID IN ( ' . implode( ', ', $post_ids ) . ')';


----------------------------------------------

Please note that due to the significant effort this reviews require, we are doing basic reviews the first time we review your plugin. Once the issues we shared above are fixed, we will do a more in-depth review that might surface other issues.

We recommend that you get ahead of us by checking for some common issues that require a more thorough review such as the use of nonces or determining plugin and content directories correctly.

Your next steps are:


Make all the corrections related to the issues we listed.
Review your entire code following best practices and the guidelines to ensure there are no other related issues.
Go to "Add your plugin" and upload an updated version of this plugin. You can update the code there whenever you need to along the review process, we will check the latest version.
Reply to this email telling us that you have updated it and letting us know if there is anything we need to know or have in mind. It is not necessary to list the changes, as we will check the whole plugin again.


To make this process as quick as possible and to avoid burden on the volunteers devoting their time to review this plugin's code, we ask you to thoroughly check all shared issues and fix them before sending the code back to us.

We encourage all plugin authors to use tools like Plugin Check to ensure that most basic issues are fixed first. If you haven't used it yet, give it a try, it will save us both time and speed up the review process.
Please note: Automated tools can give false positives, or may miss issues. Plugin Check and other tools cannot guarantee that our reviewers won't find an issue that needs fixing or clarification.

We again remind you that should you wish to alter your permalink (not the display name, the plugin slug), you must explicitly tell us what you want it to be. We require to you clearly state in the body of your email what your desired permalink is. Permalinks cannot be altered after approval, and we generally do not accept requests to rename should you fail to inform us during the review. If you previously asked for a permalink change and got a reply that is has been processed, you’re all good! While these emails will still use the original display name, you don’t need to panic. If you did not get a reply that we processed the permalink, let us know immediately.

While we have tried to make this review as exhaustive as possible we, like you, are humans and may have missed things. As such, we will re-review the entire plugin when you send it back to us. We appreciate your patience and understanding.

If the corrections we requested in this initial review are not completed within 3 months (90 days), we will reject this submission in order to keep our queue manageable.

If you have questions, concerns, or need clarification, please reply to this email and just ask us.