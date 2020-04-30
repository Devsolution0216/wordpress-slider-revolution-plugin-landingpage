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

<!--COPYRIGHT MODAL-->
<div class="rb-modal-wrapper" data-modal="rbm_copyright" style="display:none">
	<div class="rb-modal-inner">
		<div class="rb-modal-content">
			<div id="rbm_copyright" class="rb_modal form_inner">
				<div class="rbm_header"><span class="rbm_title"><?php _e('Copyright & Licensing - Slider Revolution Library', 'revslider');?></span><i class="rbm_close material-icons">close</i></div>
				<div class="rbm_content">
					<div class="rbm_content_left">
						<div class="copyright_sel selected" data-crm="templates"><i class="material-icons">aspect_ratio</i><?php _e('Templates');?></div>
						<div class="copyright_sel" data-crm="images"><i class="material-icons">photo_camera</i><?php _e('Images');?></div>
						<div class="copyright_sel" data-crm="objects"><i class="material-icons">filter_drama</i><?php _e('Objects');?></div>
						<div class="copyright_sel" data-crm="videos"><i class="material-icons">videocam</i><?php _e('Videos');?></div>
						<div class="copyright_sel" data-crm="svg"><i class="material-icons">copyright</i><?php _e('SVG');?></div>
						<div class="copyright_sel" data-crm="icon"><i class="material-icons">font_download</i><?php _e('Icon');?></div>
						<div class="copyright_sel" data-crm="layers"><i class="material-icons">layers</i><?php _e('Layers');?></div>
					</div>
					<div class="rbm_content_right">
						<div class="crm_content_wrap" id="crm_templates">
							<div class="crm_title"><?php _e('Terms of using Layer Group Objects from the Library');?></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('Templates from the Slider Revolution Library <b>must only</b> be used with a');?></br><a target="_blank" href="https://themepunch.com/faq/where-to-find-purchase-code/"><?php _e('registered purchase code');?></a> <?php _e('on that particular website.');?></div></div>							
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('Media assets used in the respective templates, are licensed according to the here mentioned license terms (see list on the left).');?></div></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('Slider Revolution Add-Ons <b>must only</b> be used with a');?> <a target="_blank" href="https://themepunch.com/faq/where-to-find-purchase-code/"><?php _e('registered purchase code');?></a> <?php _e('on that particular website.');?></div></div>
							<div class="div30"></div>
							<a target="_blank" href="https://getsliderrevolution.com" class="crm_basic_button basic_action_button autosize basic_action_coloredbutton" style="padding:0px 30px"><?php _e('Buy another License');?> <span style="line-height:28px" class="crm_infostar">*</span></a>
							<div class="crm_info_text"><span class="crm_infostar">*</span><?php _e('One License / Purchase Code is required for each Website');?></div>
						</div>
						<div class="crm_content_wrap" id="crm_images">
							<div class="crm_title"><?php _e('Terms of using JPG Images from the Library');?></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('The pictures are free for personal and even for commercial use.');?></div></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('You can modify, copy and distribute the photos. All without asking for permission or setting a link to the source. So, attribution is not required.');?></div></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('The only restriction is that identifiable people may not appear in a bad light or in a way that they may find offensive, unless they give their consent.');?></div></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('The CC0 license was released by the non-profit organization Creative Commons (CC). Get more information about Creative Commons images and the license on the official license page.');?></div></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('Images from');?> <a target="_blank" href="https://www.pexels.com/"><?php _e('Pexels');?></a> <?php _e('under the license');?> <a target="_blank" href="https://creativecommons.org/share-your-work/public-domain/cc0/"><?php _e('CC0 Creative Commons');?></a></div></div>							
						</div>
						<div class="crm_content_wrap" id="crm_objects">
							<div class="crm_title"><?php _e('Terms of using PNG Objects from the Library');?></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('PNG Objects from the Slider Revolution Library <b>must only</b> be used with a');?></br><a target="_blank" href="https://themepunch.com/faq/where-to-find-purchase-code/"><?php _e('registered purchase code');?></a> <?php _e('on that particular website.');?></div></div>							
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('Licenses via extended license and cooperation with author ');?> <a target="_blank" href="https://creativemarket.com/ceacle"><?php _e('Ceacle');?></a></div></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('If you need .psd files for objects, you can purchase it from the original author');?> <a target="_blank" href="https://creativemarket.com/ceacle"><?php _e('here');?></a></div></div>
							<div class="div30"></div>
							<a target="_blank" href="https://getsliderrevolution.com" class="crm_basic_button basic_action_button autosize basic_action_coloredbutton" style="padding:0px 30px"><?php _e('Buy another License');?> <span style="line-height:28px" class="crm_infostar">*</span></a>
							<div class="crm_info_text"><span class="crm_infostar">*</span><?php _e('One License / Purchase Code is required for each Website');?></div>
						</div>
						<div class="crm_content_wrap " id="crm_videos">
							<div class="crm_title"><?php _e('Terms of using HTML5 Videos from the Library');?></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('The videos are free for personal and even for commercial use.');?></div></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('You can modify, copy and distribute the videos. All without asking for permission or setting a link to the source. So, attribution is not required.');?></div></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('The only restriction is that identifiable people may not appear in a bad light or in a way that they may find offensive, unless they give their consent.');?></div></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('The CC0 license was released by the non-profit organization Creative Commons (CC). Get more information about Creative Commons images and the license on the official license page.');?></div></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('Videos from');?> <a target="_blank" href="https://www.pexels.com/"><?php _e('Pexels');?></a> <?php _e('under the license');?> <a target="_blank" href="https://creativecommons.org/share-your-work/public-domain/cc0/"><?php _e('CC0 Creative Commons');?></a></div></div>
						</div>
						<div class="crm_content_wrap " id="crm_svg">
							<div class="crm_title"><?php _e('Terms of using SVG Objects from the Library');?></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('Usage only allowed within Slider Revolution Plugin');?></div></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('A variety of sizes and densities can be also downloaded from the ');?> <a target="_blank" href="https://github.com/google/material-design-icons"><?php _e('git repository');?></a> <?php _e(', making it even easier for developers to customize, share, and re-use outside of Slider Revolution.');?></div></div>							
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('Licenses via Apache License. Read More at');?> <a target="_blank" href="https://github.com/google/material-design-icons/blob/master/LICENSE"><?php _e('Google Material Design Icons');?></a></div></div>
						</div>
						<div class="crm_content_wrap" id="crm_icon">
							<div class="crm_title"><?php _e('Terms of using ICON Objects from the Library');?></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('Please check the listed license files for details about how you can use the "FontAwesome" and "Stroke 7 Icon" font sets for commercial projects, open source projects, or really just about whatever you want.');?></div></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('Please respect all other icon fonts licenses for fonts not included directly into Slider Revolution.');?></div></div>
							<div class="div25"></div>
							<div class="crm_title"><?php _e('Further License Information');?></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('<b>Font Awesome 4.6.3</b> by @davegandy - http://fontawesome.io - @fontawesome <br>License -');?> <a target="_blank" href="http://fontawesome.io/license"><?php _e('http://fontawesome.io/license');?></a><?php _e('(Font: SIL OFL 1.1, CSS: MIT License)');?></div></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('<b>Stroke 7 Icon Font Set</b> by www.pixeden.com </br>Get your Freebie Iconset at');?> <a target="_blank" href="http://www.pixeden.com/icon-fonts/stroke-7-icon-font-set"><?php _e('http://www.pixeden.com/icon-fonts/stroke-7-icon-font-set');?></a></div></div>
						</div>
						<div class="crm_content_wrap selected" id="crm_layers">
							<div class="crm_title"><?php _e('Terms of using Layer Group Objects from the Library');?></div>
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('Layer Group Objects from the Slider Revolution Library <b>must only</b> be used with a');?></br><a target="_blank" href="https://themepunch.com/faq/where-to-find-purchase-code/"><?php _e('registered purchase code');?></a> <?php _e('on that particular website.');?></div></div>							
							<div class="crm_content"><div class="crm_arrow material-icons">arrow_forward</div><div class="crm_text"><?php _e('Media assets used in the respective Layer Group Objects, are licensed according to the here mentioned license terms (see list on the left).');?></div></div>							
							<div class="div30"></div>
							<a target="_blank" href="https://getsliderrevolution.com" class="crm_basic_button basic_action_button autosize basic_action_coloredbutton" style="padding:0px 30px"><?php _e('Buy another License');?> <span style="line-height:28px" class="crm_infostar">*</span></a>
							<div class="crm_info_text"><span class="crm_infostar">*</span><?php _e('One License / Purchase Code is required for each Website');?></div>
						</div>
					</div>
				</div>					
			</div>
		</div>
	</div>
</div>