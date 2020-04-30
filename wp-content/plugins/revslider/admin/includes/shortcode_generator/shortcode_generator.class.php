<?php
/**
 * @author    ThemePunch <info@themepunch.com>
 * @link      https://www.themepunch.com/
 * @copyright 2019 ThemePunch
 */
 
if(!defined('ABSPATH')) exit();

class RevSliderShortcodeWizard extends RevSliderFunctions {	
	
	
	public static function enqueue_scripts(){
		global $pagenow;

		$f = new RevSliderFunctions();
		$action = $f->get_val($_GET, 'action');
		if($action === 'elementor') return;
		
		// only add scripts if native WordPress editor, Gutenberg or Visual Composer
		// Elementor has its own hooks for adding scripts
		if($action === 'edit' || $pagenow === 'post-new.php' || $f->get_val($_GET, 'vc_action', '') === 'vc_inline'){
			self::add_scripts();
		}
		
	}
	
	public static function add_styles(){
		wp_enqueue_style('revslider-material-icons', RS_PLUGIN_URL . 'admin/assets/icons/material-icons.css', array(), RS_REVISION);
		wp_enqueue_style('revslider-basics-css', RS_PLUGIN_URL . 'admin/assets/css/basics.css', array(), RS_REVISION);
		wp_enqueue_style('rs-color-picker-css', RS_PLUGIN_URL . 'admin/assets/css/tp-color-picker.css', array(), RS_REVISION);
		wp_enqueue_style('revbuilder-select2RS', RS_PLUGIN_URL . 'admin/assets/css/select2RS.css', array(), RS_REVISION);
	}
	
	public static function add_scripts($elementor = false){
		
		$f = new RevSliderFunctions();
		$action = $f->get_val($_GET, 'action');
		if($elementor && $action !== 'elementor') return;
		
		require_once(RS_PLUGIN_PATH . 'admin/includes/functions-admin.class.php');
		require_once(RS_PLUGIN_PATH . 'admin/includes/template.class.php');
		require_once(RS_PLUGIN_PATH . 'admin/includes/folder.class.php');
		require_once(RS_PLUGIN_PATH . 'public/revslider-front.class.php');
	
		//check user permissions
		if(!current_user_can('edit_posts') && !current_user_can('edit_pages')) return;
		if(!$elementor){
			//verify the post type
			global $typenow;
			
			$post_types = get_post_types();
			if(empty($post_types) || !is_array($post_types)) $post_types = array('post', 'page');
			if(!in_array($typenow, $post_types)) return;
		
			$current_screen = get_current_screen();
			
			// checks for built-in gutenberg version
			$is_gutenberg = method_exists($current_screen, 'is_block_editor') && $current_screen->is_block_editor();
			
			// checks for old plugin version
			if(!$is_gutenberg) $is_gutenberg = function_exists('is_gutenberg_page') && is_gutenberg_page();
			
			// gutenberg
			if(!$is_gutenberg){
				add_filter('mce_external_plugins', array('RevSliderShortcodeWizard', 'add_tinymce_shortcode_editor_plugin'));
				add_filter('mce_buttons', array('RevSliderShortcodeWizard', 'add_tinymce_shortcode_editor_button'));
			}
			
			// enqueue styles
			self::add_styles();
		}else{
			// elementor 
			wp_enqueue_script('revslider-elementor', RS_PLUGIN_URL . 'admin/assets/js/shortcode_generator/elementor.js', array('jquery'), RS_REVISION, true);
		}
		
		$output_class = new RevSliderOutput();
		$output_class->add_inline_double_jquery_error(true);
		echo RevSliderFront::js_set_start_size();
		
		$dev_mode = (!file_exists(RS_PLUGIN_PATH.'admin/assets/js/plugins/utils.min.js') && !file_exists(RS_PLUGIN_PATH.'admin/assets/js/modules/editor.min.js')) ? true : false;
	
		if($dev_mode === true){			
			wp_enqueue_script('revbuilder-basics', RS_PLUGIN_URL . 'admin/assets/js/modules/basics.js', array('jquery'), RS_REVISION, false);			
			wp_enqueue_script('revbuilder-select2RS', RS_PLUGIN_URL . 'admin/assets/js/plugins/select2RS.full.min.js', array('jquery'), RS_REVISION, false);				
			wp_enqueue_script('revbuilder-color-picker-js', RS_PLUGIN_URL . 'admin/assets/js/plugins/tp-color-picker.min.js', array('jquery', 'revbuilder-select2RS', 'wp-color-picker'), RS_REVISION);
			wp_enqueue_script('revbuilder-clipboard', RS_PLUGIN_URL . 'admin/assets/js/plugins/clipboard.min.js', array('jquery'), RS_REVISION, false);
			wp_enqueue_script('revbuilder-utils', RS_PLUGIN_URL . 'admin/assets/js/modules/objectlibrary.js', array('jquery'), RS_REVISION, false);    
		}else{
			wp_enqueue_script('revbuilder-utils', RS_PLUGIN_URL . 'admin/assets/js/plugins/utils.min.js', array('jquery','wp-color-picker'), RS_REVISION, false);
		}
		
		wp_enqueue_script('tp-tools', RS_PLUGIN_URL . 'public/assets/js/revolution.tools.min.js', array('jquery'), RS_REVISION, true);
		
		// object library translations
		wp_localize_script('revbuilder-utils', 'RVS_LANG', array(
			'copyrightandlicenseinfo' => __('&copy; Copyright & License Info', 'revslider'),
			'ol_images' => __('Images', 'revslider'),
			'ol_layers' => __('Layer Objects', 'revslider'),
			'ol_objects' => __('Objects', 'revslider'),
			'ol_modules' => __('Own Modules', 'revslider'),
			'ol_fonticons' => __('Font Icons', 'revslider'),
			'ol_moduletemplates' => __('Module Templates', 'revslider'),
			'ol_videos' => __('Videos', 'revslider'),
			'ol_svgs' => __('SVG\'s', 'revslider'),
			'ol_favorite' => __('Favorites', 'revslider'),
			'simproot' => __('Root', 'revslider'),
			'loading' => __('Loading', 'revslider'),
			'elements' => __('Elements', 'revslider'),
			'loadingthumbs' => __('Loading Thumbnails...', 'revslider'),
			'moduleBIG' => __('MODULE', 'revslider'),
			'packageBIG' => __('PACKAGE', 'revslider'),
			'installed' => __('Installed', 'revslider'),
			'notinstalled' => __('Not Installed', 'revslider'),
			'setupnotes' => __('Setup Notes', 'revslider'),
			'requirements' => __('Requirements', 'revslider'),
			'installedversion' => __('Installed Version', 'revslider'),
			'availableversion' => __('Available Version', 'revslider'),
			'installpackage' => __('Installing Template Package', 'revslider'),
			'installtemplate' => __('Install Template', 'revslider'),
			'licencerequired' => __('Activate License', 'revslider'),
			'redownloadTemplate' => __('Re-Download Online', 'revslider'),
			'createBlankPage' => __('Create Blank Page', 'revslider'),
			'please_wait_a_moment' => __('Please Wait a Moment', 'revslider'),
			'search' => __('Search', 'revslider'),
			'folderBIG' => __('FOLDER', 'revslider'),
			'objectBIG' => __('OBJECT', 'revslider'),
			'imageBIG' => __('IMAGE', 'revslider'),
			'videoBIG' => __('VIDEO', 'revslider'),
			'iconBIG' => __('ICON', 'revslider'),
			'svgBIG' => __('SVG', 'revslider'),
			'fontBIG' => __('FONT', 'revslider'),
			'show' => __('Show', 'revslider'),
			'perpage' => __('Per Page', 'revslider'),
			'updatefromserver' => __('Update List', 'revslider'),
			'imageisloading' => __('Image is Loading...', 'revslider'),
			'importinglayers' => __('Importing Layers...', 'revslider'),
			'layerwithaction' => __('Layer with Action', 'revslider'),
			'triggeredby' => __('Behavior', 'revslider'),
			'nrlayersimporting' => __('Layers Importing', 'revslider'),
			'nothingselected' => __('Nothing Selected', 'revslider'),
			'sortbycreation' => __('Sort by Creation', 'revslider'),
			'creationascending' => __('Creation Ascending', 'revslider'),
			'sortbytitle' => __('Sort by Title', 'revslider'),
			'titledescending' => __('Title Descending', 'revslider'),
			'sliderasmodal' => __('Add Slider as Modal', 'revslider')
		));
		
		wp_enqueue_script('revslider-shortcode-generator-js', RS_PLUGIN_URL . 'admin/assets/js/shortcode_generator/shortcode_generator.js', array('jquery'), RS_REVISION, true);
		
		$rsaf = new RevSliderFunctionsAdmin();
		$rsa = $rsaf->get_short_library();
		
		if(!empty($rsa)) $obj = $rsaf->json_encode_client_side($rsa);
		
		$favs = get_option('rs_favorite', array());
		$favs = !empty($favs) ? $rsaf->json_encode_client_side($favs) : false;
		?>

		<div id="rb_modal_underlay" style="display:none"></div>
		
		<script type="text/javascript">
			window.RVS = window.RVS === undefined ? {F:{}, C:{}, ENV:{}, LIB:{}, V:{}, S:{}} : window.RVS;
			RVS.LIB.OBJ = RVS.LIB.OBJ===undefined ? {} : RVS.LIB.OBJ;
			
			var RS_DEFALIAS,
				RS_SHORTCODE_FAV;
			
			RVS.ENV.plugin_url = '<?php echo RS_PLUGIN_URL; ?>'; 
			RVS.ENV.plugin_dir = 'revslider';
			RVS.ENV.admin_url = '<?php echo admin_url('admin.php?page=revslider'); ?>';
			RVS.ENV.nonce = '<?php echo wp_create_nonce('revslider_actions'); ?>';
			
			window.addEventListener('load', function(){
				<?php if(!empty($rsa)){ ?>
				RVS.LIB.OBJ = {shortcode_generator: true, types: jQuery.parseJSON(<?php echo $obj; ?>)};
				<?php }else{ ?>
				RVS.LIB.OBJ = {};
				<?php } 
				if(!empty($favs)){ ?>
				RS_SHORTCODE_FAV = jQuery.parseJSON(<?php echo $favs; ?>);
				<?php } ?>
			});

		</script>
		<?php
		require_once(RS_PLUGIN_PATH . 'admin/views/modals-copyright.php');
	}
	
	/**
	 * called from revslider.php
	  * @since: 6.0
	 */
	public static function visual_composer_include(){
		
		// VC is enabled
        if(defined('WPB_VC_VERSION') && function_exists('vc_map')){
			vc_map(
				array(
					'name' => __('Revolution Slider 6', 'revslider'),
					'base' => 'rev_slider',
					'icon' => 'icon-wpb-revslider',
					'category' => __('Content', 'revslider'),
					'show_settings_on_create' => false,
					'js_view' => 'VcSliderRevolution',
					'admin_enqueue_js' => RS_PLUGIN_URL.'admin/assets/js/shortcode_generator/vc.js',
					'front_enqueue_js' => RS_PLUGIN_URL.'admin/assets/js/shortcode_generator/vc.js',
					'params' => array(
						array(
							'type' => 'rev_slider_shortcode',
							'heading' => __('Alias', 'revslider'),
							'param_name' => 'alias',
							'admin_label' => true,
							'value' => ''
						)
					)
				)
			);
		}
	}	
	
	/**
	 * add script tinymce shortcode script
	 * @since: 5.1.1
	 */
	public static function add_tinymce_shortcode_editor_plugin($plugin_array){
		$plugin_array['revslider_sc_button'] = RS_PLUGIN_URL . 'admin/assets/js/shortcode_generator/tinymce.js';
		
		return $plugin_array;
	}
	
	/**
	 * Add button to tinymce
	 * @since: 5.1.1
	 */
	public static function add_tinymce_shortcode_editor_button($buttons){
		array_push($buttons, 'revslider_sc_button');
		
		return $buttons;
	}

}

/**
 * old classname extends new one (old classnames will be obsolete soon)
 * @since: 5.0
 **/
class RevSlider_TinyBox extends RevSliderShortcodeWizard {}
class RevSliderTinyBox extends RevSlider_TinyBox {}

?>