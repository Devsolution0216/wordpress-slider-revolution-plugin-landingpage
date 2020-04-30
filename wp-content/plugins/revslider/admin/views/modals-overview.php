<?php
/**
 * Provide an admin area view for the Slider Modal Options
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 * @author    ThemePunch <info@themepunch.com>
 * @link      https://www.themepunch.com/
 * @copyright 2019 ThemePunch
 */
 
if(!defined('ABSPATH')) exit();
?>

<!--WELCOME MODAL-->
<div class="rb-modal-wrapper" data-modal="rbm_welcomeModal">
	<div class="rb-modal-inner">
		<div class="rb-modal-content">
			<div id="rbm_welcomeModal" class="rb_modal form_inner">
				<div class="rbm_header"><span class="rbm_title"><?php _e('Welcome to Slider Revolution 6', 'revslider');?></span><i class="rbm_close material-icons">close</i></div>
				<div class="rbm_content">
					<div style="padding:80px 100px 0px">
						<div id="welcome_logo"></div>
						<div class="mcg_option_third_wraps">
							<div class="st_slider mcg_guide_optionwrap mcg_option_third">																
								<div class="mcg_o_title"><?php _e('What\'s new?');?></div>
								<div class="mcg_o_descp"><?php _e('Slider Revolution recieved a complete<br>makeover with Version 6.0.');?></div>
								<div class="div25"></div>
								<a  target="_blank" href="http://revolution6.themepunch.com/direct-customer-benefits/" class="basic_action_button autosize basic_action_lilabutton"><?php _e('More Info');?></a>
							</div>
							<div class="st_scene mcg_guide_optionwrap mcg_option_third">																
								<div class="mcg_o_title"><?php _e('Docs & FAQs');?></div>
								<div class="mcg_o_descp"><?php _e('Checkout our all new Help Center<br>with updated 6.0 Support Material.');?></div>
								<div class="div25"></div>
								<a  target="_blank" href="https://www.themepunch.com/support-center" class="basic_action_button autosize basic_action_lilabutton"><?php _e('Help Center');?></a>
							</div>
							<div class="st_carousel mcg_guide_optionwrap mcg_option_third last">																
								<div class="mcg_o_title"><?php _e('Clear your Browser Cache');?></div>
								<div class="mcg_o_descp"><?php _e('To make sure that all Slider Revolution files<br>are updated, please clear your cache.');?></div>
								<div class="div25"></div>
								<a  target="_blank" href="https://www.themepunch.com/faq/updating-make-sure-clear-caches/" class="basic_action_button autosize basic_action_lilabutton"><?php _e('How to?');?></a>
							</div>
						</div>
						<div class="div75"></div>
					</div>
					<?php
					if(get_option('revslider-valid', 'false') == 'true') { ?>
						<div id="open_welcome_register_form" class="big_purple_linkbutton"><?php _e('Lets get Started with ' );?> <b> <?php _e('Slider Revolution 6.0');?></b></div>
					<?php } else { ?>
						<div id="open_welcome_register_form" class="big_purple_linkbutton"><?php _e('Activate Slider Revolution to');?> <b> <i class="material-icons">lock</i> <?php _e('Unlock all Features');?></b></div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>


<!--GLOBAL SETTINGS MODAL-->
<div class="rb-modal-wrapper" data-modal="rbm_globalsettings">
	<div class="rb-modal-inner">
		<div class="rb-modal-content">
			<div id="rbm_globalsettings" class="rb_modal form_inner">
				<div class="rbm_header"><i class="rbm_symbol material-icons">settings</i><span class="rbm_title"><?php _e('Global Settings', 'revslider');?></span><i class="rbm_close material-icons">close</i></div>
				<div class="rbm_content"><!--
					--><div class="rbm_general_half" style="padding-right:20px;">
						<div class="ale_i_title"><?php _e('General', 'revslider');?></div>
						<hr class="general_hr">
						<label_a><?php _e('Permission', 'revslider');?></label_a><select id="role" name="role" data-theme="inmodal" class="globalinput easyinit nosearchbox tos2" data-r="globals.permission">
										<option selected="selected" value="admin"><?php _e('To Admin', 'revslider');?></option>
										<option value="editor"><?php _e('To Editor, Admin', 'revslider');?></option>
										<option value="author"><?php _e('Author, Editor, Admin', 'revslider');?></option>
									</select><span class="linebreak"></span>
						<div class="div15"></div>
						<label_a><?php _e('Include libraries globally', 'revslider');?></label_a><input type="checkbox" class="easyinit globalinput" data-r="globals.allinclude"><span class="linebreak"></span>
						<label_a><?php _e('List of pages to include RevSlider libraries ', 'revslider');?></label_a><input type="text" data-r="globals.includeids" class="easyinit globalinput" placeholder="<?php _e('(ie. Example 2,homepage,5)', 'revslider');?>"><span class="linebreak"></span>
						<div class="div25"></div>					
						<label_a><?php _e('Insert scripts in footer', 'revslider');?></label_a><input type="checkbox" class="easyinit globalinput" data-r="globals.script.footer"><span class="linebreak"></span>
						<label_a><?php _e('Defer JavaScript loading', 'revslider');?></label_a><input type="checkbox" class="easyinit globalinput" data-r="globals.script.defer"><span class="linebreak"></span>
						<label_a><?php _e('3rd Party Lazy Loading Data', 'revslider');?></label_a><input type="text" class="easyinit globalinput"  data-r="globals.lazyloaddata" placeholder="<?php _e('(i.e. lazy-src for WP Rocket)', 'revslider'); ?>"><span class="linebreak"></span>																		
						<div class="div25"></div>
						<label_a><?php _e('Fix RevSlider table issues', 'revslider');?></label_a><div id="rs_db_force_create" class="basic_action_button autosize"><i class="material-icons">build</i><?php _e('Force RS DB Creation', 'revslider');?></div>
						<div class="div25"></div>
						<label_a><?php _e('Editor High Contrast Mode', 'revslider');?></label_a><input type="checkbox" class="easyinit globalinput callEvent" data-evt="highContrast" data-r="globals.highContrast"><span class="linebreak"></span>
					</div><!--
					--><div class="rbm_general_half" style="padding-left:20px;">
						<div class="ale_i_title"><?php _e('Default Layout Grid Breakpoints', 'revslider');?></div>
						<hr class="general_hr">
						<label_a><?php _e('Default desktop content width', 'revslider');?></label_a><input type="text" class="easyinit globalinput" data-numeric="true" data-allowed="px" data-min="0" data-max="2400" data-r="globals.size.desktop"><span class="linebreak"></span>
						<label_a><?php _e('Default notebook content width', 'revslider');?></label_a><input type="text" class="easyinit globalinput" data-numeric="true" data-allowed="px" data-min="0" data-max="2400" data-r="globals.size.notebook"><span class="linebreak"></span>
						<label_a><?php _e('Default tablet content width', 'revslider');?></label_a><input type="text" class="easyinit globalinput" data-numeric="true" data-allowed="px" data-min="0" data-max="2400" data-r="globals.size.tablet"><span class="linebreak"></span>
						<label_a><?php _e('Default mobile content width', 'revslider');?></label_a><input type="text" class="easyinit globalinput" data-numeric="true" data-allowed="px" data-min="0" data-max="2400" data-r="globals.size.mobile"><span class="linebreak"></span>		
						<div class="div25"></div>
						<div class="ale_i_title"><?php _e('Fonts', 'revslider');?></div>
						<hr class="general_hr">
						<label_a><?php _e('Disable RS Font Awesome Library', 'revslider');?></label_a><input type="checkbox" class="easyinit globalinput" data-r="globals.fontawesomedisable"><span class="linebreak"></span>					
						<label_a><?php _e('Optional font loading URL', 'revslider');?></label_a><input type="text" class="easyinit globalinput" data-r="globals.fonturl" placeholder="<?php _e('(ie. http://fonts.useso.com/css?family for chinese Environment)', 'revslider');?>"><span class="linebreak"></span>
						<label_a><?php _e('Enable google font download', 'revslider');?></label_a><select id="fontdownload" name="fontdownload" data-theme="inmodal" class="globalinput easyinit nosearchbox tos2" data-r="globals.fontdownload">
										<option selected="selected" value="off"><?php _e('Load from Google','revslider');?></option>
										<option value="preload"><?php _e('Preload from Google', 'revslider');?></option>
										<option value="disable"><?php _e('Disable, Load on your own', 'revslider');?></option>
									</select><span class="linebreak"></span>
						<label_a></label_a><div id="rs_trigger_font_deletion" class="basic_action_button autosize"><i class="material-icons">build</i><?php _e('Update Preload Fonts', 'revslider'); ?></div>
						<div class="div25"></div>
						<label_a><?php _e('Enable custom font selection in editor<br><i>Example:CustomA, CustomB</i>', 'revslider');?></label_a><input type="text" class="easyinit globalinput" data-r="globals.customfonts" placeholder="<?php _e('font-family, font-family, ...', 'revslider');?>"><span class="linebreak"></span>
						<!--<div id="general_custom_fonts_list"></div>
						<label_a></label_a><div class="basic_action_button onlyicon" id="add_custom_global_fonts"><i class="material-icons">add</i></div>		-->
					</div>
				</div>	
				
				<div id="rbm_globalsettings_savebtn"><i class="material-icons mr10">save</i><span class="rbm_cp_save_text"><?php _e('Save Global Settings', 'revslider');?></span></div>
			</div>
		</div>
	</div>
</div>
