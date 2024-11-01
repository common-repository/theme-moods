=== Theme Moods ===
Contributors: Philip M. Hofer (Frumph)
Tags: theme, moods, post, fun, extra
Requires at least: 2.8
Tested up to: 2.8.5
Stable tag: 0.1.0
Donate link: http://webcomicplanet.com/faqs/donate/

This plugin is used to add moods to a themes post. (some theme editing required)

== Description ==

Theme Moods plugin is intended to assist users of their themes to add as a bonus visualization to their themes increasing their fun factor.

To include Theme Moods in your theme add with your post loop:

`
if (function_exists('frumph_show_mood_in_post')) frumph_show_mood_in_post();
`

The CSS to include in your style.css file is:

`
.post-mood, .post-moodname  
`

Where moodname would = the name of the mood file.

Within your post loop where you would like the mood to appear.

Future revisions will include more mood packs as well as the ability to make custom ones.

== Screenshots ==

1. A display of the post-editor screen.
2. Example positioning within a post for the mood.


== Changelog ==  

= 0.1.0 =
Release creation version.

== Installation ==

1. Upload the `theme-moods` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Edit your theme to add the appropriate code line and make it look how you want.
1. Add CSS elements to your style.css
1. Go to Appearance -> Theme Moods to set your settings.
1. Edit your post to see the current mood set and set the mood of your post.

== Frequently Asked Questions ==

= I'm having another problem. =

File a bug on the [WebComic Planet Forums](http://webcomicplanet.com/forum/theme-moods/). Make sure you're running the most recent stable version of Theme Moods, as there are a lot of critical bug fixes between versions.

If it's a serious problem, such as WordPress becoming non-functional or blank screens/errors after you perform certain operations, you will be asked to provide error logs for your server.  If you don't know how to get access to these, talk with your Webhost.  At that time just remove the plugin from the plugins/ directory.  If it still
continue's it is probably not a Theme Moods problem.

== License ==

Theme Moods is released under the GNU GPL version 3.0 or later.

== Credits ==

Mondo thanks to John Bintz and Tyler Martin for their assistance, bug finding, assistance and collaboration.

