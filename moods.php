<?php
/*
Plugin Name: Theme Moods
Plugin URI: http://webcomicplanet.com/forum/theme-moods/
Description: Add moods to a themes post.
Version: 0.1.0
Author: Philip M. Hofer (Frumph)
Author URI: http://frumph.net/

Copyright 2009 Philip M. Hofer (Frumph)  (email : philip@frumph.net)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

$thememoods_directory = dirname (__FILE__);

register_activation_hook(__FILE__,'thememoods_activation');
register_deactivation_hook(__FILE__,'thememoods_deactivation');
add_action('admin_menu', 'thememoods_add_menu_link');

// Add menu page
function thememoods_add_menu_link() {
	$pagehook = add_submenu_page('themes.php','Theme Moods', 'Theme Moods', 10, 'thememoods', 'thememoods_admin_page');
//	add_action('admin_head-'.$pagehook, 'thememoods_config_page_head');
}

function thememoods_activation() {
	$options = array();
	$options = get_option('thememoods');
	if (empty($options)) {
		$options['moodset'] = 'default';
		add_option('thememoods', $options, '', 'yes');
	}
}

function thememoods_deactivation() {
	delete_option('thememoods');
}


function frumph_show_mood_in_post() {
	global $post, $thememoods_directory;
	$mood_options = get_option('thememoods');
	
	$moodset = $mood_options['moodset'];
	if (empty($moodset)) $moodset = 'default';
	
	$moods_directory = $thememoods_directory . '/images/'.$moodset;
	
	if (!empty($moodset) && $moodset != 'none') {
		$mood_file = get_post_meta( get_the_ID(), "mood", true );
		if (!empty($mood_file) && $mood_file != '') {
			$mood = explode(".", $mood);
			$mood = reset($mood);
			if ( !empty($mood_file) && file_exists($thememoods_directory . '/images/'.$moodset.'/'.$mood_file) ) {
				$imagedir =  plugin_dir_url($thememoods_directory); ?>
				<div class="post-mood post-<?php echo $mood; ?>"><img src="<?php echo $imagedir; ?>/theme-moods/images/<?php echo $moodset; ?>/<?php echo $mood_file; ?>" alt="<?php echo $mood; ?>" title="<?php echo $mood; ?>" /></div>
			<?php } 
		}
	}
}

function frumph_showmood_edit_post() { 
	global $post, $thememoods_directory;
	$mood_options = get_option('thememoods');
	
	$moodset = $mood_options['moodset'];
	if (empty($moodset)) $moodset = 'default';
	
	$moods_directory = $thememoods_directory . '/images/'.$moodset;
	
	if (!empty($moodset) && $moodset != 'none') { ?>
		<div class="inside" style="overflow: hidden">
		<?php _e('Available Moods, you can set which mood images to use in the frumph Options.','frumph'); ?><br />
		<br />
		<?php 
		
		$currentmood = get_post_meta( $post->ID, "mood", true );
		
		if (empty($currentmood) || $currentmood == '' || $currentmood == null) { 
			$mood = __('none','frumph');
		} else {
			$mood = explode(".", $currentmood);
			$mood = reset($mood);
		}
		
		$filtered_glob_results = array();
		$count = count($results = glob($thememoods_directory . '/images/'.$moodset.'/*'));
		echo $count .__(' moods are available.','frumph').'<br />
				'.__('Using Moods from directory: ','frumph').$moods_directory.'<br />
				'.__('Current Mood: ','frumph').$mood.'<br /><br />';
		if (!empty($results)) { ?>
			<div style="float:left; margin-top: 70px; text-align: center; width: 68px; overflow: hidden;"> 
			<label for="postmood-none" style="cursor:pointer;">		
			none
			</label>
			<br />
			<input name="postmood" style="margin-top: 3px;" id="postmood-anger" type="radio" value="none" <?php if ( $mood == 'none' ) { echo " checked"; } ?> />
			</div>
			<?php foreach ($results as $file) {
				$newmood_file = basename($file);
				$newmood = explode(".", $newmood_file); 
				$newmood = $newmood[0]; ?>
				<div style="float:left; margin-top: 10px; text-align: center; width: 68px; overflow: hidden;"> 
				<label for="postmood-<?php echo $newmood; ?>" style="cursor:pointer;">
				<?php $imagedir =  plugin_dir_url($thememoods_directory); ?>
				<img src="<?php echo $imagedir ?>/theme-moods/images/<?php echo $moodset; ?>/<?php echo basename($file); ?>" />
				<?php echo $newmood; ?>
				</label>
				<br />
				<input  name="postmood" style="margin-top: 3px;" id="postmood-<?php echo $newmood; ?>" type="radio" value="<?php echo $newmood_file; ?>"<?php if ( $mood == $newmood ) { echo " checked"; } ?> />
				</div>
			<?php }
		} ?>
		</div>
	<?php }
}


function frumph_moods_handle_edit_post_save($post_id) {
	$mood_options = get_option('thememoods');
	
	$moodset = $mood_options['moodset'];

	if (empty($_POST['postmood']) || $_POST['postmood'] == 'none') {
		$postmood = 'none';
	} else {
		$postmood = $_POST['postmood'];
	}
		update_post_meta($post_id, 'mood', $postmood);
}

function frumph_mood_admin_function() {
	add_meta_box(
			'mood-for-this-post',
			__('Mood For This Post', 'frumph'),
			'frumph_showmood_edit_post',
			'post',
			'normal',
			'low'
			);
}

add_action('admin_menu', 'frumph_mood_admin_function');
// add_action('edit_form_advanced', 'frumph_showmood_edit_post', 5, 1);
add_action('save_post', 'frumph_moods_handle_edit_post_save' ,5, 1);

function thememoods_admin_page() {
	global $themetricks_directory;	

	$options = get_option('thememoods');
	
	if ( wp_verify_nonce($_POST['_wpnonce'], 'update-options') ) {
		if ('thememoods_save_settings' == $_REQUEST['action'] ) {
			// Our first value is either 0 or 1
			$input = array();
			$input['moodset'] = $_REQUEST['moodset'];
			update_option('thememoods',$input);
		}
		
	}
?>
	<div style="clear:both;"></div>
	<h2>Theme Moods</h2>
	<div class="wrap">
		<h3>General Settings</h3>
		<div class="stuffbox">	
			<div class="inside">
				<form method="post" id="myForm" name="template">
				<?php wp_nonce_field('update-options') ?>
				<?php $options = get_option('thememoods'); ?>
					<table class="form-table">
						<tr>
							<td valign="top" width="100"><strong>None</strong></td>
							<td valign="top"><input name="moodset" type="radio" value="none" <?php if ($options['moodset'] == 'none') { ?> Checked<?php } ?> /></td>
						</tr>
						<tr>
							<td valign="top" width="100"><strong>Default</strong></td>
							<td valign="top"><input name="moodset" type="radio" value="default" <?php if ($options['moodset'] == 'default') { ?> Checked<?php } ?> /></td>
						</tr>
					</table>
					<p class="submit" style="margin-left: 10px;">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
					<input type="hidden" name="action" value="thememoods_save_settings" />
					</p>
				</form>

			</div>
	<div style="float: left;">
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="8010001">
		<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form> 
	</div>
	<div style="float: left;">Donate to help continue producing WordPress Plugins and support existing.<br />
	Estimated value of Page Tricks WordPress plugin is $5.00<br />
	You can find assistance for Page Tricks installation and bug reporting at<br />
	<a href="http://webcomicplanet.com/forum/theme-moods/">WebComic Planet Forums.</a><br />
	</div>
	<div style="clear: both;"></div>
	</div>
	<br />Theme Moods is made by <a href="http://frumph.net/">Philip M. Hofer (Frumph)</a>.
	</div>
	
	</div>
	
	
	<?php
}
?>