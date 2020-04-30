<?php
/**
 * @author    ThemePunch <info@themepunch.com>
 * @link      https://www.themepunch.com/
 * @copyright 2019 ThemePunch
 */

if(!defined('ABSPATH')) exit();

class RevSliderAdmin extends RevSliderFunctions {
	//private $theme_mode = false;
	private $view			 = 'slider';
	private $user_role		 = 'admin';
	private $global_settings = array();
	private $screens		 = array(); //holds all RevSlider Relevant screens in it
	private $allowed_views	 = array('sliders', 'slider', 'slide', 'update'); //holds pages, that are allowed to be included
	private $pages			 = array('revslider'); //, 'revslider_navigation', 'rev_addon', 'revslider_global_settings'
	private $dev_mode		 = false;
	private $path_views;

	/**
	 * construct admin part
	 **/
	public function __construct(){
		parent::__construct();
		
		if(!file_exists(RS_PLUGIN_PATH.'admin/assets/js/plugins/utils.min.js') && !file_exists(RS_PLUGIN_PATH.'admin/assets/js/modules/editor.min.js')){
			$this->dev_mode = true;
		}
		
		$this->path_views = RS_PLUGIN_PATH . 'admin/views/';
		$this->global_settings = $this->get_global_settings();
		
		$this->set_current_page();
		$this->set_user_role();
		$this->do_update_checks();
		$this->add_actions();
		$this->add_filters();
	}
	
	/**
	 * enqueue all admin styles
	 **/
	public function enqueue_admin_styles(){
		wp_enqueue_style('rs-open-sans', '//fonts.googleapis.com/css?family=Open+Sans:400,300,700,600,800');
		//wp_enqueue_style('revslider-global-styles', RS_PLUGIN_URL . 'admin/assets/css/global.css', array(), RS_REVISION);
		wp_enqueue_style(array('wp-jquery-ui', 'wp-jquery-ui-core', 'wp-jquery-ui-dialog', 'wp-color-picker'));
		wp_enqueue_style('revbuilder-color-picker-css', RS_PLUGIN_URL . 'admin/assets/css/tp-color-picker.css', array(), RS_REVISION);
		
		if(in_array($this->get_val($_GET, 'page'), $this->pages)){
			wp_enqueue_style('revbuilder-select2RS', RS_PLUGIN_URL . 'admin/assets/css/select2RS.css', array(), RS_REVISION);
			//wp_enqueue_style('codemirror-css', RS_PLUGIN_URL .'admin/assets/css/codemirror.css', array(), RS_REVISION);
			wp_enqueue_style('rs-frontend-settings', RS_PLUGIN_URL . 'public/assets/css/rs6.css', array(), RS_REVISION);
			wp_enqueue_style('rs-icon-set-fa-icon-', RS_PLUGIN_URL . 'public/assets/fonts/font-awesome/css/font-awesome.css', array(), RS_REVISION);
			wp_enqueue_style('rs-icon-set-pe-7s-', RS_PLUGIN_URL . 'public/assets/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css', array(), RS_REVISION);
			wp_enqueue_style('revslider-basics-css', RS_PLUGIN_URL . 'admin/assets/css/basics.css', array(), RS_REVISION); //'rs-new-plugin-settings'
			wp_enqueue_style('rs-new-plugin-settings', RS_PLUGIN_URL . 'admin/assets/css/builder.css', array('revslider-basics-css'), RS_REVISION);
			if(is_rtl()){
				wp_enqueue_style('rs-new-plugin-settings-rtl', RS_PLUGIN_URL . 'admin/assets/css/builder-rtl.css', array('rs-new-plugin-settings'), RS_REVISION);
			}
		}
	}

	/**
	 * enqueue all admin scripts
	 **/
	public function enqueue_admin_scripts(){
		if(function_exists('wp_enqueue_media')){
			wp_enqueue_media();
		}

		wp_enqueue_script(array('jquery', 'jquery-ui-core', 'jquery-ui-mouse', 'jquery-ui-accordion', 'jquery-ui-datepicker', 'jquery-ui-dialog', 'jquery-ui-slider', 'jquery-ui-autocomplete', 'jquery-ui-sortable', 'jquery-ui-droppable', 'jquery-ui-tabs', 'jquery-ui-widget', 'wp-color-picker', 'wpdialogs', 'updates'));
		wp_enqueue_script(array('wp-color-picker'));
		/**
		 * The script is already auto-enqueued via 'add_tinymce_shortcode_editor_plugin'
		 **/
		/*
		//enqueue in all pages / posts in backend
		$screen = get_current_screen();

		$post_types = get_post_types('', 'names');
		foreach($post_types as $post_type){
			if($post_type == $screen->id){
				wp_enqueue_script('revslider-tinymce-shortcode-script', RS_PLUGIN_URL . 'admin/assets/js/modules/tinymce-shortcode-script.js', array('jquery'), RS_REVISION, true);
			}
		}
		*/

		if(in_array($this->get_val($_GET, 'page'), $this->pages)){
			global $wp_scripts;
			$view = $this->get_val($_GET, 'view');

			wp_enqueue_script('jquery-ui-droppable', array('jquery'), RS_REVISION);
			
			/**
			 * dequeue tp-tools to make sure that always the latest is loaded
			 **/
			if(version_compare($this->get_val($wp_scripts, array('registered', 'tp-tools', 'ver'), '1.0'), RS_TP_TOOLS, '<')){
				wp_deregister_script('tp-tools');
				wp_dequeue_script('tp-tools');
			}
			
			wp_enqueue_script('tp-tools', RS_PLUGIN_URL . 'public/assets/js/revolution.tools.min.js', array(), RS_TP_TOOLS);
			
			if($this->dev_mode){
				wp_enqueue_script('revbuilder-admin', RS_PLUGIN_URL . 'admin/assets/js/modules/admin.js', array('jquery'), RS_REVISION, false);
				wp_localize_script('revbuilder-admin', 'RVS_LANG', $this->get_javascript_multilanguage()); //Load multilanguage for JavaScript
				wp_enqueue_script('revbuilder-basics', RS_PLUGIN_URL . 'admin/assets/js/modules/basics.js', array('jquery'), RS_REVISION, false);
				wp_enqueue_script('revbuilder-select2RS', RS_PLUGIN_URL . 'admin/assets/js/plugins/select2RS.full.min.js', array('jquery'), RS_REVISION, false);
				wp_enqueue_script('revbuilder-color-picker-js', RS_PLUGIN_URL . 'admin/assets/js/plugins/tp-color-picker.min.js', array('jquery', 'revbuilder-select2RS', 'wp-color-picker'), RS_REVISION);
				wp_enqueue_script('revbuilder-clipboard', RS_PLUGIN_URL . 'admin/assets/js/plugins/clipboard.min.js', array('jquery'), RS_REVISION, false);
				wp_enqueue_script('revbuilder-objectlibrary', RS_PLUGIN_URL . 'admin/assets/js/modules/objectlibrary.js', array('jquery'), RS_REVISION, false);
			}else{
				wp_enqueue_script('revbuilder-admin', RS_PLUGIN_URL . 'admin/assets/js/modules/admin.min.js', array('jquery'), RS_REVISION, false);
				wp_localize_script('revbuilder-admin', 'RVS_LANG', $this->get_javascript_multilanguage()); //Load multilanguage for JavaScript
				wp_enqueue_script('revbuilder-utils', RS_PLUGIN_URL . 'admin/assets/js/plugins/utils.min.js', array('jquery', 'wp-color-picker'), RS_REVISION, false);
			}
			
			if($view == 'slide' && $this->dev_mode){
				wp_enqueue_script('revbuilder-help', RS_PLUGIN_URL . 'admin/assets/js/modules/helpinit.js', array('jquery', 'revbuilder-admin'), RS_REVISION, false);
				wp_enqueue_script('revbuilder-toolbar', RS_PLUGIN_URL . 'admin/assets/js/modules/rightclick.js', array('jquery', 'revbuilder-admin'), RS_REVISION, false);
				wp_enqueue_script('revbuilder-effects', RS_PLUGIN_URL . 'admin/assets/js/modules/timeline.js', array('jquery','revbuilder-admin'), RS_REVISION, false);
				wp_enqueue_script('revbuilder-layer', RS_PLUGIN_URL . 'admin/assets/js/modules/layer.js', array('jquery','revbuilder-admin'), RS_REVISION, false);
				wp_enqueue_script('revbuilder-layertools', RS_PLUGIN_URL . 'admin/assets/js/modules/layertools.js', array('jquery','revbuilder-admin'), RS_REVISION, false);
				wp_enqueue_script('revbuilder-quick-style', RS_PLUGIN_URL . 'admin/assets/js/modules/quickstyle.js', array('jquery','revbuilder-admin'), RS_REVISION, false);
				wp_enqueue_script('revbuilder-navigations', RS_PLUGIN_URL . 'admin/assets/js/modules/navigation.js', array('jquery','revbuilder-admin'), RS_REVISION, false);
				wp_enqueue_script('revbuilder-layeractions', RS_PLUGIN_URL . 'admin/assets/js/modules/layeractions.js', array('jquery','revbuilder-admin'), RS_REVISION, false);
				wp_enqueue_script('revbuilder-layerlist', RS_PLUGIN_URL . 'admin/assets/js/modules/layerlist.js', array('jquery','revbuilder-admin'), RS_REVISION, false);
				wp_enqueue_script('revbuilder-slide', RS_PLUGIN_URL . 'admin/assets/js/modules/slide.js', array('jquery','revbuilder-admin'), RS_REVISION, false);
				wp_enqueue_script('revbuilder-slider', RS_PLUGIN_URL . 'admin/assets/js/modules/slider.js', array('jquery','revbuilder-admin'), RS_REVISION, false);
				wp_enqueue_script('revbuilder', RS_PLUGIN_URL . 'admin/assets/js/builder.js', array('jquery','revbuilder-admin', 'jquery-ui-sortable'), RS_REVISION, false);
			}elseif($view == 'slide' && !$this->dev_mode){
				wp_enqueue_script('revbuilder-editor', RS_PLUGIN_URL . 'admin/assets/js/modules/editor.min.js', array('jquery', 'revbuilder-admin', 'jquery-ui-sortable'), RS_REVISION, false);
			}

			if($view == '' || $view == 'sliders'){
				if($this->dev_mode) {
					wp_enqueue_script('revbuilder-overview', RS_PLUGIN_URL . 'admin/assets/js/modules/overview.js', array('jquery'), RS_REVISION, false);
				} else {
					wp_enqueue_script('revbuilder-overview', RS_PLUGIN_URL . 'admin/assets/js/modules/overview.min.js', array('jquery'), RS_REVISION, false);
				}
				
				if(!file_exists(RS_PLUGIN_PATH.'public/assets/js/rs6.min.js')){
					wp_enqueue_script('revmin', RS_PLUGIN_URL . 'public/assets/js/dev/rs6.main.js', 'tp-tools', RS_REVISION, false);
					//if on, load all libraries instead of dynamically loading them
					wp_enqueue_script('revmin-actions', RS_PLUGIN_URL . 'public/assets/js/dev/rs6.actions.js', 'tp-tools', RS_REVISION, false);
					wp_enqueue_script('revmin-carousel', RS_PLUGIN_URL . 'public/assets/js/dev/rs6.carousel.js', 'tp-tools', RS_REVISION, false);
					wp_enqueue_script('revmin-layeranimation', RS_PLUGIN_URL . 'public/assets/js/dev/rs6.layeranimation.js', 'tp-tools', RS_REVISION, false);
					wp_enqueue_script('revmin-navigation', RS_PLUGIN_URL . 'public/assets/js/dev/rs6.navigation.js', 'tp-tools', RS_REVISION, false);
					wp_enqueue_script('revmin-panzoom', RS_PLUGIN_URL . 'public/assets/js/dev/rs6.panzoom.js', 'tp-tools', RS_REVISION, false);
					wp_enqueue_script('revmin-parallax', RS_PLUGIN_URL . 'public/assets/js/dev/rs6.parallax.js', 'tp-tools', RS_REVISION, false);
					wp_enqueue_script('revmin-slideanims', RS_PLUGIN_URL . 'public/assets/js/dev/rs6.slideanims.js', 'tp-tools', RS_REVISION, false);
					wp_enqueue_script('revmin-video', RS_PLUGIN_URL . 'public/assets/js/dev/rs6.video.js', 'tp-tools', RS_REVISION, false);
				}else{
					wp_enqueue_script('revmin', RS_PLUGIN_URL . 'public/assets/js/rs6.min.js', array('jquery', 'tp-tools'), RS_REVISION, false);
				}
			}
		}
		
		//include all media upload scripts
		$this->add_media_upload_includes();
	}

	/**
	 * add all js and css needed for media upload
	 */
	protected static function add_media_upload_includes(){
		if(function_exists('wp_enqueue_media')){
			wp_enqueue_media();
		}

		wp_enqueue_script('thickbox');
		wp_enqueue_script('media-upload');
		wp_enqueue_style('thickbox');
	}
	
	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain(){
		load_plugin_textdomain('revslider', false, dirname(RS_PLUGIN_SLUG_PATH) . '/languages/');
		load_plugin_textdomain('revsliderhelp', false, dirname(RS_PLUGIN_SLUG_PATH) . '/languages/');
	}

	/**
	 * set the user role, to restrict plugin usage to certain groups
	 * @since: 6.0
	 **/
	public function set_user_role(){
		$this->user_role = $this->get_val($this->global_settings, 'permission', 'admin');
	}

	/**
	 * add the admin pages to the WordPress backend
	 * @since: 6.0
	 **/
	public function add_admin_pages(){
		switch ($this->user_role){
		case 'author':
			$role = 'edit_published_posts';
			break;
		case 'editor':
			$role = 'edit_pages';
			break;
		default:
		case 'admin':
			$role = 'manage_options';
			break;
		}

		$this->screens[] = add_menu_page('Slider Revolution', 'Slider Revolution', $role, 'revslider', array($this, 'display_admin_page'), 'dashicons-update');
	}

	/**
	 * add wildcards metabox variables to posts
	 * @var $post_types: null = all, post = only posts
	 */
	public function add_slider_meta_box($post_types = null){
		try {
			add_meta_box('mymetabox_revslider_0', 'Slider Revolution Options', array('RevSliderAdmin', 'add_meta_box_content'), $post_types, 'normal', 'default');
		} catch (Exception $e){}
	}

	/**
	 * on add metabox content
	 */
	public static function add_meta_box_content($post, $boxData){
		call_user_func(array('RevSliderAdmin', 'custom_post_fields_output'));
	}

	/**
	 *  custom output function
	 */
	public static function custom_post_fields_output(){
		$slider = new RevSliderSlider();
		$output = array();
		$output['default'] = 'default';

		$meta = get_post_meta(get_the_ID(), 'slide_template', true);
		$meta = ($meta == '') ? 'default' : $meta;
		
		$slides = $slider->get_sliders_with_slides_short('template');
		$output = $output + $slides; //union arrays
		?>
		<ul class="revslider_settings">
			<li id="slide_template_row">
				<div title="" class="setting_text" id="slide_template_text"><?php _e('Choose Slide Template', 'revslider');?></div>
				<div class="setting_input">
					<select name="slide_template" id="slide_template">
						<?php
						foreach($output as $handle => $name){
							echo '<option ' . selected($handle, $meta) . ' value="' . $handle . '">' . $name . '</option>';
						}
						?>
					</select>
				</div>
				<div class="clear"></div>
			</li>
		</ul>
		<?php
	}
	
	
	
	/**
	 * 
	 * on save post meta. Update metaboxes data from post, add it to the post meta 
	 * @before: RevSliderBaseAdmin::onSavePost();
	 */
	public static function on_save_post(){
		$f = new RevSliderFunctions();
		
		$post_id = $f->get_post_var('ID');
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id; //protection against autosave
		if(empty($post_id)) return false;
		
		$value = $f->get_post_var('slide_template');
		update_post_meta($post_id, 'slide_template', $value);
	}
	
	
	/**
	 * we dont want to show notices in our plugin
	 **/
	public function hide_notices(){
		if(in_array($this->get_val($_GET, 'page'), $this->pages)){
			remove_all_actions('admin_notices');
		}
	}

	/**
	 * check if we need to search for updates, if yes. Do them
	 **/
	private function do_update_checks(){
		$upgrade	= new RevSliderUpdate(RS_REVISION);
		$library	= new RevSliderObjectLibrary();
		$template	= new RevSliderTemplate();
		$validated	= get_option('revslider-valid', 'false');
		$stablev	= get_option('revslider-stable-version', '0');

		$uol = (isset($_REQUEST['update_object_library'])) ? true : false;
		$library->_get_list($uol);
		
		$us = (isset($_REQUEST['update_shop'])) ? true : false;
		$template->_get_template_list($us);

		$upgrade->force = (in_array($this->get_val($_REQUEST, 'checkforupdates', 'false'), array('true', true), true)) ? true : false;
		$upgrade->_retrieve_version_info();
		
		if($validated === 'true' || version_compare(RS_REVISION, $stablev, '<')){
			$upgrade->add_update_checks();
		}
	}

	/**
	 * Add Classes to the WordPress body
	 * @since    6.0
	 */
	function modify_admin_body_class($classes){
		$classes .= ($this->get_val($_GET, 'page') == 'revslider' && $this->get_val($_GET, 'view') == 'slide') ? ' rs-builder-mode' : '';
		$classes .= ($this->_truefalse($this->get_val($this->global_settings, 'highContrast', false)) === true && $this->get_val($_GET, 'page') === 'revslider') ? ' rs-high-contrast' : '';
		
		return $classes;
	}


	/**
	 * Add all actions that the backend needs here
	 **/
	public function add_actions(){
		global $pagenow;
		
		add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
		add_action('admin_head', array($this, 'hide_notices'), 1);
		add_action('admin_menu', array($this, 'add_admin_pages'));
		add_action('add_meta_boxes', array($this, 'add_slider_meta_box'));
		add_action('save_post', array($this, 'on_save_post'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
		add_action('wp_ajax_revslider_ajax_action', array($this, 'do_ajax_action')); //ajax response to save slider options.
		add_action('wp_ajax_revslider_ajax_call_front', array($this, 'do_front_ajax_action'));
		add_action('wp_ajax_nopriv_revslider_ajax_call_front', array($this, 'do_front_ajax_action')); //for not logged in users

		if(isset($pagenow) && $pagenow == 'plugins.php'){
			add_action('admin_notices', array($this, 'add_plugins_page_notices'));
		}
		
		add_action('admin_init', array($this, 'add_suggested_privacy_content'), 15);
	}

	/**
	 * Add all filters that the backend needs here
	 **/
	public function add_filters(){
		add_filter('admin_body_class', array($this, 'modify_admin_body_class'));
	}

	/**
	 * add plugin notices to the Slider Revolution Plugin at the overview page of plugins
	 **/
	public static function add_plugins_page_notices(){
		$plugins = get_plugins();

		foreach($plugins as $plugin_id => $plugin){
			$slug = dirname($plugin_id);
			if(empty($slug) || $slug !== 'revslider'){
				continue;
			}

			$add = (get_option('revslider-valid', 'false') == 'false' || version_compare(get_option('revslider-latest-version', RS_REVISION), $plugin['Version'], '>')) ? true : false;
			
			if($add){
				add_action('after_plugin_row_' . $plugin_id, array('RevSliderAdmin', 'add_notice_wrap_pre'), 10, 3);
			}

			//check version, latest updates and if registered or not
			if(get_option('revslider-valid', 'false') == 'false'){
				//activate for updates and support
				add_action('after_plugin_row_' . $plugin_id, array('RevSliderAdmin', 'show_purchase_notice'), 10, 3);
			}
			
			if($add){
				add_action('after_plugin_row_' . $plugin_id, array('RevSliderAdmin', 'add_notice_wrap_post'), 10, 3);
			}
		}
	}

	/**
	 * Add the pre HTML for plugin notice on the plugin overview page
	 **/
	public static function add_notice_wrap_pre($plugin_file, $plugin_data, $plugin_status){
		$wp_list_table = _get_list_table('WP_Plugins_List_Table');
		$slug = dirname($plugin_file);
		if(is_network_admin()){
			$active_class = is_plugin_active_for_network($plugin_file) ? ' active' : '';
		}else{
			$active_class = is_plugin_active($plugin_file) ? ' active' : '';
		}
		
		?>
		<tr class="plugin-update-tr<?php echo $active_class; ?>" id="<?php echo $slug; ?>-update" data-plugin="<?php echo $plugin_file; ?>"><td colspan="<?php echo $wp_list_table->get_column_count(); ?>" class="plugin-update colspanchange">
			<div class="update-message notice inline notice-warning notice-alt">
		<?php
	}

	/**
	 * Add the post HTML for plugin notice on the plugin overview page
	 **/
	public static function add_notice_wrap_post($plugin_file, $plugin_data, $plugin_status){
		?>
			</div>
		</tr>
		<?php
	}

	/**
	 * Show message for activation benefits
	 **/
	public static function show_purchase_notice($plugin_file, $plugin_data, $plugin_status){
		?>
		<p>
			<?php _e('Activate Slider Revolution for <a href="https://revolution.themepunch.com/direct-customer-benefits/" target="_blank">Premium Benefits (e.g. Live Updates)</a>.', 'revslider');?>
		</p>
        <?php
	}

	/**
	 * Add the suggested privacy policy text to the policy postbox.
	 */
	public function add_suggested_privacy_content() {
		if(function_exists('wp_add_privacy_policy_content')){
			$content = $this->get_default_privacy_content();
			wp_add_privacy_policy_content(__( 'Slider Revolution'), $content);
		}
	}
	
	/**
	 * Return the default suggested privacy policy content.
	 *
	 * @return string The default policy content.
	 */
	public function get_default_privacy_content(){
		return __('<h2>In case you’re using Google Web Fonts (default) or playing videos or sounds via YouTube or Vimeo in Slider Revolution we recommend to add the corresponding text phrase to your privacy police:</h2>
		<h3>YouTube</h3> <p>Our website uses plugins from YouTube, which is operated by Google. The operator of the pages is YouTube LLC, 901 Cherry Ave., San Bruno, CA 94066, USA.</p> <p>If you visit one of our pages featuring a YouTube plugin, a connection to the YouTube servers is established. Here the YouTube server is informed about which of our pages you have visited.</p> <p>If you\'re logged in to your YouTube account, YouTube allows you to associate your browsing behavior directly with your personal profile. You can prevent this by logging out of your YouTube account.</p> <p>YouTube is used to help make our website appealing. This constitutes a justified interest pursuant to Art. 6 (1) (f) DSGVO.</p> <p>Further information about handling user data, can be found in the data protection declaration of YouTube under <a href="https://www.google.de/intl/de/policies/privacy" target="_blank">https://www.google.de/intl/de/policies/privacy</a>.</p>
		<h3>Vimeo</h3> <p>Our website uses features provided by the Vimeo video portal. This service is provided by Vimeo Inc., 555 West 18th Street, New York, New York 10011, USA.</p> <p>If you visit one of our pages featuring a Vimeo plugin, a connection to the Vimeo servers is established. Here the Vimeo server is informed about which of our pages you have visited. In addition, Vimeo will receive your IP address. This also applies if you are not logged in to Vimeo when you visit our plugin or do not have a Vimeo account. The information is transmitted to a Vimeo server in the US, where it is stored.</p> <p>If you are logged in to your Vimeo account, Vimeo allows you to associate your browsing behavior directly with your personal profile. You can prevent this by logging out of your Vimeo account.</p> <p>For more information on how to handle user data, please refer to the Vimeo Privacy Policy at <a href="https://vimeo.com/privacy" target="_blank">https://vimeo.com/privacy</a>.</p>
		<h3>Google Web Fonts</h3> <p>For uniform representation of fonts, this page uses web fonts provided by Google. When you open a page, your browser loads the required web fonts into your browser cache to display texts and fonts correctly.</p> <p>For this purpose your browser has to establish a direct connection to Google servers. Google thus becomes aware that our web page was accessed via your IP address. The use of Google Web fonts is done in the interest of a uniform and attractive presentation of our plugin. This constitutes a justified interest pursuant to Art. 6 (1) (f) DSGVO.</p> <p>If your browser does not support web fonts, a standard font is used by your computer.</p> <p>Further information about handling user data, can be found at <a href="https://developers.google.com/fonts/faq" target="_blank">https://developers.google.com/fonts/faq</a> and in Google\'s privacy policy at <a href="https://www.google.com/policies/privacy/" target="_blank">https://www.google.com/policies/privacy/</a>.</p>
		<h3>SoundCloud</h3><p>On our pages, plugins of the SoundCloud social network (SoundCloud Limited, Berners House, 47-48 Berners Street, London W1T 3NF, UK) may be integrated. The SoundCloud plugins can be recognized by the SoundCloud logo on our site.</p>
			<p>When you visit our site, a direct connection between your browser and the SoundCloud server is established via the plugin. This enables SoundCloud to receive information that you have visited our site from your IP address. If you click on the “Like” or “Share” buttons while you are logged into your SoundCloud account, you can link the content of our pages to your SoundCloud profile. This means that SoundCloud can associate visits to our pages with your user account. We would like to point out that, as the provider of these pages, we have no knowledge of the content of the data transmitted or how it will be used by SoundCloud. For more information on SoundCloud’s privacy policy, please go to https://soundcloud.com/pages/privacy.</p><p>If you do not want SoundCloud to associate your visit to our site with your SoundCloud account, please log out of your SoundCloud account.</p>', 'revslider');
	}

	/**
	 * The Ajax Action part for backend actions only
	 **/
	public function do_ajax_action(){
		@ini_set('memory_limit', apply_filters('admin_memory_limit', WP_MAX_MEMORY_LIMIT));

		$slider	= new RevSliderSlider();
		$slide	= new RevSliderSlide();

		$action	= $this->get_request_var('client_action');
		$data	= $this->get_request_var('data');
		$data	= ($data == '') ? array() : $data;
		$nonce	= $this->get_request_var('nonce');
		$nonce	= (empty($nonce)) ? $this->get_request_var('rs-nonce') : $nonce;

		try{
			if(RS_DEMO){
				switch ($action){
					case 'get_template_information_short':
					case 'import_template_slider':
					case 'install_template_slider':
					case 'install_template_slide':
					case 'get_list_of':
					case 'get_global_settings':
					case 'get_full_slider_object':
					case 'subscribe_to_newsletter':
					case 'check_system':
					case 'load_module':
					case 'get_addon_list':
					case 'get_layers_by_slide':
					case 'silent_slider_update':
					case 'get_help_directory':
					case 'set_tooltip_preference':
					case 'load_builder':
					case 'load_library_object':
					case 'get_tooltips':
					//case 'preview_slider':
						//these are all okay in demo mode
					break;
					default:
						$this->ajax_response_error(__('Function Not Available in Demo Mode', 'revslider'));
						exit;
					break;
				}
			}

			if(!current_user_can('administrator') && apply_filters('revslider_restrict_role', true)){
				switch($action){
					case 'activate_plugin':
					case 'deactivate_plugin':
					case 'import_template_slider':
					case 'install_template_slider':
					case 'install_template_slide':
					case 'import_slider':
					case 'delete_slider':
					case 'create_navigation_preset':
					case 'delete_navigation_preset':
					case 'save_navigation':
					case 'delete_animation':
					case 'save_animation':
					case 'check_system':
					case 'fix_database_issues':
					case 'trigger_font_deletion':
					case 'get_v5_slider_list':
					case 'reimport_v5_slider':
						$this->ajax_response_error(__('Function Only Available for Adminstrators', 'revslider'));
						exit;
					break;
					default:
						$return = apply_filters('revslider_admin_onAjaxAction_user_restriction', true, $action, $data, $slider, $slide, $operations);
						if($return !== true){
							$this->ajax_response_error(__('Function Only Available for Adminstrators', 'revslider'));
							exit;
						}
					break;
				}
			}

			if(wp_verify_nonce($nonce, 'revslider_actions') == false){
				//check if it is wp nonce and if the action is refresh nonce
				$this->ajax_response_error(__('Bad Request', 'revslider'));
				exit;
			}

			switch($action){
				case 'activate_plugin':
					$result = false;
					$code = trim($this->get_val($data, 'code'));
					if(!empty($code)){
						$result = $this->activate_plugin($code);
					}else{
						$this->ajax_response_error(__('The Purchase Code needs to be set!', 'revslider'));
						exit;
					}

					if($result === true){
						$this->ajax_response_success(__('Plugin successfully activated', 'revslider'));
					}elseif($result === false){
						$this->ajax_response_error(__('Purchase Code is invalid', 'revslider'));
					}else{
						if($result == 'exist'){
							$this->ajax_response_error(__('Purchase Code already registered!', 'revslider'));
						}elseif($result == 'banned'){
							$this->ajax_response_error(__('Purchase Code was locked, please contact the ThemePunch support!', 'revslider'));
						}
						$this->ajax_response_error(__('Purchase Code could not be validated', 'revslider'));
					}
				break;
				case 'deactivate_plugin':
					$result = $this->deactivate_plugin();

					if($result){
						$this->ajax_response_success(__('Plugin deregistered', 'revslider'));
					}else{
						$this->ajax_response_error(__('Deregistration failed!', 'revslider'));
					}
				break;
				case 'dismiss_dynamic_notice':
					$ids = $this->get_val($data, 'id', array());
					$notices_discarded = get_option('revslider-notices-dc', array());
					if(!empty($ids)){
						foreach($ids as $_id){
							$notices_discarded[] = esc_attr(trim($_id));
						}
						
						update_option('revslider-notices-dc', $notices_discarded);
					}
					
					$this->ajax_response_success(__('Saved', 'revslider'));
				break;
				case 'check_for_updates':
					$update = new RevSliderUpdate(RS_REVISION);
					$update->force = true;
					
					$update->_retrieve_version_info();
					$version = get_option('revslider-latest-version', RS_REVISION);
					
					if($version !== false){
						$this->ajax_response_data(array('version' => $version));
					}else{
						$this->ajax_response_error(__('Connection to Update Server Failed', 'revslider'));
					}
				break;
				case 'get_template_information_short':
					$templates = new RevSliderTemplate();
					$sliders = $templates->get_tp_template_sliders();

					$this->ajax_response_data(array('templates' => $sliders));
				break;
				/*case 'get_template_slides':
						$slider_id		 = $this->get_val($data, 'slider_id');
						$templates		 = new RevSliderTemplate();
						$template_slider = $slider->init_by_id($slider_id);
						$slides			 = $templates->get_tp_template_slides($template_slider);

						$this->ajax_response_data(array('template_slides' => $slides));
				break;*/
				case 'import_template_slider': //before: import_slider_template_slidersview
					$uid		= $this->get_val($data, 'uid');
					$install	= $this->get_val($data, 'install', true);
					$templates	= new RevSliderTemplate();
					$filepath	= $templates->_download_template($uid);

					if($filepath !== false){
						$templates->remove_old_template($uid);
						$slider = new RevSliderSliderImport();
						$return = $slider->import_slider(false, $filepath, $uid, false, true, $install);
						
						if($this->get_val($return, 'success') == true){
							$new_id = $this->get_val($return, 'sliderID');
							if(intval($new_id) > 0){
								$map = $this->get_val($return, 'map',  array());
								$folder_id = $this->get_val($data, 'folderid', -1);
								if(intval($folder_id) > 0){
									$folder = new RevSliderFolder();
									$folder->add_slider_to_folder($new_id, $folder_id, false);
								}

								$new_slider = new RevSliderSlider();
								$new_slider->init_by_id($new_id);
								$data = $new_slider->get_overview_data();

								$hiddensliderid = $templates->get_slider_id_by_uid($uid);
								
								$templates->_delete_template($uid); //delete template file
								
								$this->ajax_response_data(array('slider' => $data, 'hiddensliderid' => $hiddensliderid, 'map' => $map, 'uid' => $uid));
							}
						}
						
						$templates->_delete_template($uid); //delete template file
						
						$error = ($this->get_val($return, 'error') !== '') ? $this->get_val($return, 'error') : __('Slider Import Failed', 'revslider');
						$this->ajax_response_error($error);
					}
					$this->ajax_response_error(__('Template Slider Import Failed', 'revslider'));
				break;
				case 'install_template_slider':
					$id = $this->get_val($data, 'sliderid');
					$new_id = $slider->duplicate_slider_by_id($id, true);
					if(intval($new_id) > 0){
						$new_slider = new RevSliderSlider();
						$new_slider->init_by_id($new_id);
						$data = $new_slider->get_overview_data();
						$slide_maps = $slider->get_map();
						$map = array(
							'slider' => array('template_to_duplication' => array($id => $new_id)),
							'slides' => $slide_maps
						);
						$this->ajax_response_data(array('slider' => $data, 'hiddensliderid' => $id, 'map' => $map));
					}
					$this->ajax_response_error(__('Template Slider Installation Failed', 'revslider'));
				break;
				case 'install_template_slide':
					$template = new RevSliderTemplate();
					$slider_id = intval($this->get_val($data, 'slider_id'));
					$slide_id = intval($this->get_val($data, 'slide_id'));

					if($slider_id == 0 || $slide_id == 0){
					}else{
						$new_slide_id = $slide->duplicate_slide_by_id($slide_id, $slider_id);

						if($new_slide_id !== false){
							$slide->init_by_id($new_slide_id);
							$_slides[] = array(
								'order' => $slide->get_order(),
								'params' => $slide->get_params(),
								'layers' => $slide->get_layers(),
								'id' => $slide->get_id(),
							);

							$this->ajax_response_data(array('slides' => $_slides));
						}
					}

					$this->ajax_response_error(__('Slide duplication failed', 'revslider'));
				break;
				case 'import_slider':
					$import = new RevSliderSliderImport();
					$return = $import->import_slider();

					if($this->get_val($return, 'success') == true){
						$new_id = $this->get_val($return, 'sliderID');

						if(intval($new_id) > 0){
							$folder = new RevSliderFolder();
							$folder_id = $this->get_val($data, 'folderid', -1);
							if(intval($folder_id) > 0){
								$folder->add_slider_to_folder($new_id, $folder_id, false);
							}

							$new_slider = new RevSliderSlider();
							$new_slider->init_by_id($new_id);
							$data = $new_slider->get_overview_data();

							$this->ajax_response_data(array('slider' => $data, 'hiddensliderid' => $new_id));
						}
					}

					$error = ($this->get_val($return, 'error') !== '') ? $this->get_val($return, 'error') : __('Slider Import Failed', 'revslider');

					$this->ajax_response_error($error);
				break;
				case 'adjust_modal_ids':
					$map = $this->get_val($data, 'map', array());
					
					if(!empty($map)){
						$slider_map = array();
						$slider_ids = $this->get_val($map, 'slider_map', array());
						$slides_ids = $this->get_val($map, 'slides_map', array());
						
						$ztt = $this->get_val($slider_ids, 'zip_to_template', array());
						$ztd = $this->get_val($slider_ids, 'zip_to_duplication', array());
						$ttd = $this->get_val($slider_ids, 'template_to_duplication', array());
						$s_a = array();
						if(!empty($slides_ids)){
							foreach($slides_ids as $k => $v){
								if(is_array($v)){
									foreach($v as $vk => $vv){
										$s_a[$vk] = $vv;
									}
									unset($slides_ids[$k]);
								}
							}
						}
						
						if(!empty($ztt)){
							foreach($ztt as $old => $new){
								$slider = new RevSliderSliderImport();
								$slider->init_by_id($new);
								
								$slider->update_modal_ids($ztt, $slides_ids);
							}
						}
						
						if(!empty($ztd)){
							foreach($ztd as $old => $new){
								$slider = new RevSliderSliderImport();
								$slider->init_by_id($new);
								$slider->update_modal_ids($ztd, $s_a);
							}
						}
						
						if(!empty($ttd)){
							foreach($ttd as $old => $new){
								$slider = new RevSliderSliderImport();
								$slider->init_by_id($new);
								$slider->update_modal_ids($ttd, $slides_ids);
							}
						}
						
						$this->ajax_response_data(array());
					}else{
						$this->ajax_response_error(__('Slider Map Empty', 'revslider'));
					}
				break;
				case 'adjust_js_css_ids':
					$map = $this->get_val($data, 'map', array());
					
					if(!empty($map)){
						$slider_map = array();
						foreach($map as $m){
							$slider_ids = $this->get_val($m, 'slider_map', array());
							if(!empty($slider_ids)){
								foreach($slider_ids as $old => $new){
									$slider = new RevSliderSliderImport();
									$slider->init_by_id($new);
									
									$slider_map[] = $slider;
								}
							}
						}
						
						if(!empty($slider_map)){
							foreach($slider_map as $slider){
								foreach($map as $m){
									$slider_ids = $this->get_val($m, 'slider_map', array());
									$slide_ids = $this->get_val($m, 'slide_map', array());
									if(!empty($slider_ids)){
										foreach($slider_ids as $old => $new){
											$slider->update_css_and_javascript_ids($old, $new, $slide_ids);
										}
									}
								}
							}
						}
					}
				break;
				case 'export_slider':
					$export = new RevSliderSliderExport();
					$id = intval($this->get_request_var('id'));

					$return = $export->export_slider($id);

					//will never be called if all is good
					$this->ajax_response_data($return);
				break;
				case 'export_slider_html':
					$export = new RevSliderSliderExportHtml();
					$id = intval($this->get_request_var('id'));
					$return = $export->export_slider_html($id);

					//will never be called if all is good
					$this->ajax_response_data($return);
				break;
				case 'delete_slider':
					$id = $this->get_val($data, 'id');
					$slider->init_by_id($id);
					$result = $slider->delete_slider();

					$this->ajax_response_success(__('Slider Deleted', 'revslider'));
				break;
				case 'duplicate_slider':
					$id = $this->get_val($data, 'id');
					$new_id = $slider->duplicate_slider_by_id($id);
					if(intval($new_id) > 0){
						$new_slider = new RevSliderSlider();
						$new_slider->init_by_id($new_id);
						$data = $new_slider->get_overview_data();
						$this->ajax_response_data(array('slider' => $data));
					}

					$this->ajax_repsone_error(__('Duplication Failed', 'revslider'));
				break;
				case 'save_slide':
					$slide_id = $this->get_val($data, 'slide_id');
					$slider_id = $this->get_val($data, 'slider_id');
					$return = $slide->save_slide($slide_id, $data, $slider_id);

					if($return){
						$this->ajax_response_success(__('Slide Saved', 'revslider'));
					}else{
						$this->ajax_response_error(__('Slide not found', 'revslider'));
					}
				break;
				case 'save_slide_advanced':
					$slide_id = $this->get_val($data, 'slide_id');
					$slider_id = $this->get_val($data, 'slider_id');
					$return = $slide->save_slide_advanced($slide_id, $data, $slider_id);
					
					if($return){
						$this->ajax_response_success(__('Slide Saved', 'revslider'));
					}else{
						$this->ajax_response_error(__('Slide not found', 'revslider'));
					}
				break;
				case 'save_slider':
					$slider_id = $this->get_val($data, 'slider_id');
					$slide_ids = $this->get_val($data, 'slide_ids', array());
					$return = $slider->save_slider($slider_id, $data);
					$missing_slides = array();
					$delete_slides = array();

					if($return !== false){
						if(!empty($slide_ids)){
							$slides = $slider->get_slides(false, true);

							//get the missing Slides (if any at all)
							foreach($slide_ids as $slide_id){
								$found = false;
								foreach($slides as $_slide){
									if($_slide->get_id() !== $slide_id){
										continue;
									}

									$found = true;
								}
								if(!$found){
									$missing_slides[] = $slide_id;
								}

							}

							//get the Slides that are no longer needed and delete them
							foreach($slides as $key => $_slide){
								$id = $_slide->get_id();
								if(!in_array($id, $slide_ids)){
									$delete_slides[] = $id;
									unset($slides[$key]); //remove none existing slides for further ordering process
								}
							}

							if(!empty($delete_slides)){
								foreach($delete_slides as $delete_slide){
									$slide->delete_slide_by_id($delete_slide);
								}
							}

							//change the order of slides
							foreach($slide_ids as $order => $id){
								$new_order = $order + 1;
								$_slide->change_slide_order($id, $new_order);
							}
						}

						$this->ajax_response_data(array('missing' => $missing_slides, 'delete' => $delete_slides));
					}else{
						$this->ajax_response_error(__('Slider not found', 'revslider'));
					}
				break;
				case 'delete_slide':
					$slide_id = intval($this->get_val($data, 'slide_id', ''));
					$return = ($slide_id > 0) ? $slide->delete_slide_by_id($slide_id) : false;
					
					if($return !== false){
						$this->ajax_response_success(__('Slide deleted', 'revslider'));
					}else{
						$this->ajax_response_error(__('Slide could not be deleted', 'revslider'));
					}
				break;
				case 'duplicate_slide':
					$slide_id	= intval($this->get_val($data, 'slide_id', ''));
					$slider_id	= intval($this->get_val($data, 'slider_id', ''));
					
					$new_slide_id = $slide->duplicate_slide_by_id($slide_id, $slider_id);
					if($new_slide_id !== false){
						$slide->init_by_id($new_slide_id);
						$_slide = $slide->get_overview_data();
						
						$this->ajax_response_data(array('slide' => $_slide));
					}else{
						$this->ajax_response_error(__('Slide could not duplicated', 'revslider'));
					}
				break;
				case 'update_slide_order':
					$slide_ids	= $this->get_val($data, 'slide_ids', array());
					
					//change the order of slides
					if(!empty($slide_ids)){
						foreach($slide_ids as $order => $id){
							$new_order = $order + 1;
							$slide->change_slide_order($id, $new_order);
						}
						
						$this->ajax_response_success(__('Slide order changed', 'revslider'));
					}else{
						$this->ajax_response_error(__('Slide order could not be changed', 'revslider'));
					}
				break;
				case 'getSliderImage':
					// Available Sliders
					$slider = new RevSliderSlider();
					$arrSliders = $slider->get_sliders();
					$post60		= (version_compare($slider->get_setting('version', '1.0.0'), '6.0.0', '<')) ? false : true;
					// Given Alias
					$alias = $this->get_val($data, 'alias');
					
					$return = array_search($alias,$arrSliders);

					foreach($arrSliders as $sliderony){
						if( $sliderony->get_alias() == $alias ){
							$slider_found = $sliderony->get_overview_data();
							$return = $slider_found["bg"]["src"];
						}
					}
					
					$this->ajax_response_data(array('image' => $return));
				break;
				case 'get_list_of':
					$type = $this->get_val($data, 'type');
					switch($type){
						case 'sliders':
							$slider = new RevSliderSlider();
							$arrSliders = $slider->get_sliders();
							$return = array();
							foreach($arrSliders as $sliderony){
								$return[$sliderony->get_id()] = array('slug' => $sliderony->get_alias(), 'title' => $sliderony->get_title(), 'type' => $sliderony->get_type(), 'subtype' => $sliderony->get_param(array('source', 'post', 'subType'), false));
							}
							$this->ajax_response_data(array('sliders' => $return));
						break;
						case 'pages':
							$pages = get_pages(array());
							$return = array();
							foreach($pages as $page){
								if(!$page->post_password){
									$return[$page->ID] = array('slug' => $page->post_name, 'title' => $page->post_title);
								}

							}
							$this->ajax_response_data(array('pages' => $return));
						break;
						case 'posttypes':
							$args = array(
								'public' => true,
								'_builtin' => false,
							);
							$output = 'objects';
							$operator = 'and';
							$post_types = get_post_types($args, $output, $operator);
							$return['post'] = array('slug' => 'post', 'title' => __('Posts', 'revslider'));

							foreach($post_types as $post_type){
								$return[$post_type->rewrite['slug']] = array('slug' => $post_type->rewrite['slug'], 'title' => $post_type->labels->name);
								if(!in_array($post_type->name, array('post', 'page', 'attachment', 'revision', 'nav_menu_item', 'custom_css', 'custom_changeset', 'user_request'))){
									$taxonomy_objects = get_object_taxonomies($post_type->name, 'objects');
									if(!empty($taxonomy_objects)){
										$return[$post_type->rewrite['slug']]['tax'] = array();
										foreach($taxonomy_objects as $name => $tax){
											$return[$post_type->rewrite['slug']]['tax'][$name] = $tax->label;
										}
									}
								}
							}

							$this->ajax_response_data(array('posttypes' => $return));
						break;
						case 'media_library_images':
							$args = array(
									'post_type' => 'attachment',
									'post_mime_type' => 'image',
									'orderby' => 'post_date',
									'order' => 'desc',
									'posts_per_page' => '-1',
									'post_status'    => 'inherit'
									);
							
							$loop = new WP_Query( $args );
							$return = array();
							$sizes = get_intermediate_image_sizes();
							$sizes[] = 'full';
							while ( $loop->have_posts() ) {
								$loop->the_post();
								foreach ( $sizes as $size ) {
									$return[get_the_ID()][$size] = json_encode(wp_get_attachment_image_src( get_the_ID(),$size)); 
								}
								//echo "<img src='" . $image[0] . "'>";
							}

							$this->ajax_response_data(array('images' => $return));
						break;
					}
				break;
				case 'get_global_settings':
					$this->ajax_response_data(array('global_settings' => $this->global_settings));
				break;
				case 'update_global_settings':
					$global = $this->get_val($data, 'global_settings', array());
					if(!empty($global)){
						$return = $this->set_global_settings($global);
						if($return === true){
							$this->ajax_response_success(__('Global Settings saved/updated', 'revslider'));
						}else{
							$this->ajax_response_error(__('Global Settings not saved/updated', 'revslider'));
						}
					}else{
						$this->ajax_response_error(__('Global Settings not saved/updated', 'revslider'));
					}
				break;
				case 'create_navigation_preset':
					$nav = new RevSliderNavigation();
					$return = $nav->add_preset($data);

					if($return === true){
						$this->ajax_response_success(__('Navigation preset saved/updated', 'revslider'), array('navs' => $nav->get_all_navigations_builder()));
					}else{
						if($return === false){
							$return = __('Preset could not be saved/values are the same', 'revslider');
						}

						$this->ajax_response_error($return);
					}
				break;
				case 'delete_navigation_preset':
					$nav = new RevSliderNavigation();
					$return = $nav->delete_preset($data);

					if($return === true){
						$this->ajax_response_success(__('Navigation preset deleted', 'revslider'), array('navs' => $nav->get_all_navigations_builder()));
					}else{
						if($return === false){
							$return = __('Preset not found', 'revslider');
						}

						$this->ajax_response_error($return);
					}
				break;
				case 'save_navigation': //also deletes if requested
					$_nav = new RevSliderNavigation();
					$navs = (array) $this->get_val($data, 'navs', array());
					$delete_navs = (array) $this->get_val($data, 'delete', array());

					if(!empty($delete_navs)){
						foreach($delete_navs as $dnav){
							$_nav->delete_navigation($dnav);
						}
					}

					if(!empty($navs)){
						$_nav->create_update_full_navigation($navs);
					}

					$navigations = $_nav->get_all_navigations_builder();

					$this->ajax_response_data(array('navs' => $navigations));
				break;
				case 'delete_animation':
					$animation_id = $this->get_val($data, 'id');
					$admin = new RevSliderFunctionsAdmin();
					$return = $admin->delete_animation($animation_id);
					if($return){
						$this->ajax_response_success(__('Animation deleted', 'revslider'));
					}else{
						$this->ajax_response_error(__('Deletion failed', 'revslider'));
					}
				break;
				case 'save_animation':
					$admin = new RevSliderFunctionsAdmin();
					$id = $this->get_val($data, 'id', false);
					$animation = $this->get_val($data, 'obj');
					$type = $this->get_val($data, 'type', 'in');

					if($id !== false){
						$return = $admin->update_animation($id, $animation, $type);
					}else{
						$return = $admin->insert_animation($animation, $type);
					}

					if(intval($return) > 0){
						$this->ajax_response_data(array('id' => $return));
					} elseif($return === true){
						$this->ajax_response_success(__('Animation saved', 'revslider'));
					}else{
						if($return == false){
							$this->ajax_response_error(__('Animation could not be saved', 'revslider'));
						}
						$this->ajax_response_error($return);
					}
				break;
				case 'get_slides_by_slider_id':
					$sid	 = intval($this->get_val($data, 'id'));
					$slides	 = array();
					$_slides = $slide->get_slides_by_slider_id($sid);
					
					if(!empty($_slides)){
						foreach($_slides as $slide){
							$slides[] = $slide->get_overview_data();
						}
					}
					
					$this->ajax_response_data(array('slides' => $slides));
				break;
				case 'get_full_slider_object':
					$slide_id = $this->get_val($data, 'id');
					$slide_id = RevSliderFunctions::esc_attr_deep($slide_id);
					$slider_alias = $this->get_val($data, 'alias', '');
					$slider_alias = RevSliderFunctions::esc_attr_deep($slider_alias);
					
					if($slider_alias !== ''){
						$slider->init_by_alias($slider_alias);
						$slider_id = $slider->get_id();
					}else{
						if(strpos($slide_id, 'slider-') !== false){
							$slider_id = str_replace('slider-', '', $slide_id);
						}else{
							$slide->init_by_id($slide_id);

							$slider_id = $slide->get_slider_id();
							if(intval($slider_id) == 0){
								$this->ajax_response_error(__('Slider could not be loaded', 'revslider'));
							}
						}
						
						$slider->init_by_id($slider_id);
					}
					if($slider->inited === false){
						$this->ajax_response_error(__('Slider could not be loaded', 'revslider'));
					}
					
					$static_slide_id = $slide->get_static_slide_id($slider_id);

					if(intval($static_slide_id) === 0){
						//create static Slide if the Slider not yet has one
						$static_slide_id = $slide->create_slide($slider_id, '', true);
					}

					$static_slide = new RevSliderSlide();
					if(intval($static_slide_id) > 0){
						$static_slide->init_by_static_id($static_slide_id);
					}else{
						$static_slide = false;
					}
					
					$slides = $slider->get_slides(false, true);
					$_slides = array();
					$_static_slide = array();

					if(!empty($slides)){
						foreach($slides as $s){
							$_slides[] = array(
								'order' => $s->get_order(),
								'params' => $s->get_params(),
								'layers' => $s->get_layers(),
								'id' => $s->get_id(),
							);
						}
					}

					if(!empty($static_slide)){
						$_static_slide = array(
							'params' => $static_slide->get_params(),
							'layers' => $static_slide->get_layers(),
							'id' => $static_slide->get_id(),
						);
					}
					
					$obj = array(
						'id' => $slider_id,
						'alias' => $slider->get_alias(),
						'title' => $slider->get_title(),
						'slider_params' => $slider->get_params(),
						'slider_settings' => $slider->get_settings(),
						'slides' => $_slides,
						'static_slide' => $_static_slide,
					);

					$this->ajax_response_data($obj);
				break;
				case 'load_builder':
					ob_start();
					require_once RS_PLUGIN_PATH . 'admin/views/builder.php';
					$builder = ob_get_contents();
					ob_clean();
					ob_end_clean();

					$this->ajax_response_data($builder);
				break;
				case 'create_slider_folder':
					$folder = new RevSliderFolder();
					$title = $this->get_val($data, 'title', __('New Folder', 'revslider'));
					$new = $folder->create_folder($title);

					if($new !== false){
						$overview_data = $new->get_overview_data();
						$this->ajax_response_data(array('folder' => $overview_data));
					}else{
						$this->ajax_response_error(__('Folder Creation Failed', 'revslider'));
					}
				break;
				case 'delete_slider_folder':
					$id = $this->get_val($data, 'id');
					$folder = new RevSliderFolder();
					$is = $folder->init_folder_by_id($id);
					if($is === true){
						$folder->delete_slider();
						$this->ajax_response_success(__('Folder Deleted', 'revslider'));
					}else{
						$this->ajax_response_error(__('Folder Deletion Failed', 'revslider'));
					}
				break;
				case 'update_slider_tags':
					$id = $this->get_val($data, 'id');
					$tags = $this->get_val($data, 'tags');

					$return = $slider->update_slider_tags($id, $tags);
					if($return == true){
						$this->ajax_response_success(__('Tags Updated', 'revslider'));
					}else{
						$this->ajax_response_error(__('Failed to Update Tags', 'revslider'));
					}
				break;
				case 'save_slider_folder':
					$folder = new RevSliderFolder();
					$children = $this->get_val($data, 'children');
					$folder_id = $this->get_val($data, 'id');

					$return = $folder->add_slider_to_folder($children, $folder_id);

					if($return == true){
						$this->ajax_response_success(__('Slider Moved to Folder', 'revslider'));
					}else{
						$this->ajax_response_error(__('Failed to Move Slider Into Folder', 'revslider'));
					}
				break;
				case 'update_slider_name':
				case 'update_folder_name':
					$slider_id = $this->get_val($data, 'id');
					$new_title = $this->get_val($data, 'title');

					$slider->init_by_id($slider_id, $new_title);
					$return = $slider->update_title($new_title);
					if($return != false){
						$this->ajax_response_data(array('title' => $return), __('Title updated', 'revslider'));
					}else{
						$this->ajax_response_error(__('Failed to update Title', 'revslider'));
					}
				break;
				case 'preview_slider':
					$slider_id = $this->get_val($data, 'id');
					$slider_data = $this->get_val($data, 'data');
					$title = __('Slider Revolution Preview', 'revslider');
					
					if(intval($slider_id) > 0 && empty($slider_data)){
						$slider->init_by_id($slider_id);

						//check if an update is needed
						if(version_compare($slider->get_param(array('settings', 'version')), get_option('revslider_update_revision', '6.0.0'), '<')){
							$upd = new RevSliderPluginUpdate();
							$upd->upgrade_slider_to_latest($slider);
							$slider->init_by_id($slider_id);
						}

						$content = '[rev_slider alias="' . esc_attr($slider->get_alias()) . '"][/rev_slider]';
					}elseif(!empty($slider_data)){
						$_slides = array();
						$_static = array();
						$slides = array();
						$static_slide = array();
						
						$_slider = array(
							'id'		=> $slider_id,
							'title'		=> 'Preview',
							'alias'		=> 'preview',
							'settings'	=> json_encode(array('version' => RS_REVISION)),
							'params'	=> stripslashes($this->get_val($slider_data, 'slider'))
						);
						
						$order = 0;
						foreach($slider_data as $sk => $sd){
							if($sk === 'slider') continue;
							
							if(strpos($sk, 'static_') !== false){
								$_static = array(
									'params' => stripslashes($this->get_val($sd, 'params')),
									'layers' => stripslashes($this->get_val($sd, 'layers')),
								);
							}else{
								$_slides[$sk] = array(
									'id'		=> $sk,
									'slider_id'	=> $slider_id,
									'slide_order' => $order,
									'params'	=> stripslashes($this->get_val($sd, 'params')),
									'layers'	=> stripslashes($this->get_val($sd, 'layers')),
									'settings'	=> array('version' => RS_REVISION)
								);
							}
							$order++;
						}
						
						$output = new RevSliderOutput();
						$slider->init_by_data($_slider);
						if($slider->is_stream() || $slider->is_posts()){
							$slides = $slider->get_slides_for_output();
						}else{
							if(!empty($_slides)){
								foreach($_slides as $_slide){
									$slide = new RevSliderSlide();
									$slide->init_by_data($_slide);
									$slides[] = $slide;
								}
							}
						}
						if(!empty($_static)){
							$slide = new RevSliderSlide();
							$slide->init_by_data($_static);
							$static_slide = $slide;
						}
						
						$output->set_slider($slider);
						$output->set_current_slides($slides);
						$output->set_static_slide($static_slide);
						$output->set_preview_mode(true);
						
						ob_start();
						$slider = $output->add_slider_to_stage($slider_id);
						$content = ob_get_contents();
						ob_clean();
						ob_end_clean();
					}
					
					//get dimensions of slider
					$size = array(
						'width'	 => $slider->get_param(array('size', 'width'), array()),
						'height' => $slider->get_param(array('size', 'height'), array())
					);
					
					if(empty($size['width'])){
						$size['width'] = array(
							'd' => $this->get_val($this->global_settings, array('size', 'desktop'), '1240'),
							'n' => $this->get_val($this->global_settings, array('size', 'notebook'), '1024'),
							't' => $this->get_val($this->global_settings, array('size', 'tablet'), '778'),
							'm' => $this->get_val($this->global_settings, array('size', 'mobile'), '480')
						);
					}
					if(empty($size['height'])){
						$size['height'] = array('d' => '868', 'n' => '768', 't' => '960', 'm' => '720'); 
					}
					
					global $revslider_is_preview_mode;
					$revslider_is_preview_mode = true;
					require_once(RS_PLUGIN_PATH . 'public/includes/functions-public.class.php');
					$rev_slider_front = new RevSliderFront();
					
					$post = $this->create_fake_post($content, $title);
					
					ob_start();
					include(RS_PLUGIN_PATH . 'public/views/revslider-page-template.php');
					$html = ob_get_contents();
					ob_clean();
					ob_end_clean();
					
					$this->ajax_response_data(array('html' => $html, 'size' => $size));
					
					exit;
				break;
				case 'subscribe_to_newsletter':
					$email = $this->get_val($data, 'email');
					if(!empty($email)){
						$return = ThemePunch_Newsletter::subscribe($email);

						if($return !== false){
							if(!isset($return['status']) || $return['status'] === 'error'){
								$error = $this->get_val($return, 'message', __('Invalid Email', 'revslider'));
								$this->ajax_response_error($error);
							}else{
								$this->ajax_response_success(__('Success! Please check your E-Mails to finish the subscription', 'revslider'), $return);
							}
						}
						$this->ajax_response_error(__('Invalid Email/Could not connect to the Newsletter server', 'revslider'));
					}

					$this->ajax_response_error(__('No Email given', 'revslider'));
				break;
				case 'check_system':
					//recheck the connection to themepunch server
					$update = new RevSliderUpdate(RS_REVISION);
					$update->force = true;
					$update->_retrieve_version_info();

					$fun = new RevSliderFunctionsAdmin();
					$system = $fun->get_system_requirements();

					$this->ajax_response_data(array('system' => $system));
				break;
				case 'load_module':
					$module = $this->get_val($data, 'module', array('all'));
					$module_uid = $this->get_val($data, 'module_uid', false);
					$module_slider_id = $this->get_val($data, 'module_id', false);
					$refresh_from_server = $this->get_val($data, 'refresh_from_server', false);
					$get_static_slide = $this->_truefalse($this->get_val($data, 'static', false));
					
					if($module_uid === false){
						$module_uid = $module_slider_id;
					}

					$admin = new RevSliderFunctionsAdmin();
					$modules = $admin->get_full_library($module, $module_uid, $refresh_from_server, $get_static_slide);
					
					$this->ajax_response_data(array('modules' => $modules));
				break;
				case 'set_favorite':
					$do = $this->get_val($data, 'do', 'add');
					$type = $this->get_val($data, 'type', 'slider');
					$id = esc_attr($this->get_val($data, 'id'));

					$favorite = new RevSliderFavorite();
					$favorite->set_favorite($do, $type, $id);

					$this->ajax_response_success(__('Favorite Changed', 'revslider'));
				break;
				case 'load_library_object':
					$library = new RevSliderObjectLibrary();

					$cover = false;
					$id = $this->get_val($data, 'id');
					$type = $this->get_val($data, 'type');
					if($type == 'thumb'){
						$thumb = $library->_get_object_thumb($id, 'thumb');
					}elseif($type == 'video'){
						$thumb = $library->_get_object_thumb($id, 'video_full', true);
						$cover = $library->_get_object_thumb($id, 'cover', true);
					}elseif($type == 'layers'){
						$thumb = $library->_get_object_layers($id);
					}else{
						$thumb = $library->_get_object_thumb($id, 'orig', true);
						if(isset($thumb['error']) && $thumb['error'] === false){
							$orig = $this->get_val($thumb, 'url', false);
							$url = $library->get_correct_size_url($id, $type);
							if($url !== ''){
								$thumb['url'] = $url;
							}
						}
					}

					if(isset($thumb['error']) && $thumb['error'] !== false){
						$this->ajax_response_error(__('Object could not be loaded', 'revslider'));
					}else{
						if($type == 'layers'){
							$return = array('layers' => $this->get_val($thumb, 'data'));
						}else{
							$return = array('url' => $this->get_val($thumb, 'url'));
						}

						if($cover !== false){
							if(isset($cover['error']) && $cover['error'] !== false){
								$this->ajax_response_error(__('Video cover could not be loaded', 'revslider'));
							}

							$return['cover'] = $this->get_val($cover, 'url');
						}

						$this->ajax_response_data($return);
					}
				break;
				case 'create_slide':
					$slider_id = $this->get_val($data, 'slider_id', false);
					$amount = $this->get_val($data, 'amount', 1);
					$amount = intval($amount);
					$slide_ids = array();

					if(intval($slider_id) > 0 && ($amount > 0 && $amount < 50)){
						for ($i = 0; $i < $amount; $i++){
							$slide_ids[] = $slide->create_slide($slider_id);
						}
					}

					if(!empty($slide_ids)){
						$this->ajax_response_data(array('slide_id' => $slide_ids));
					}else{
						$this->ajax_response_error(__('Could not create Slide', 'revslider'));
					}
				break;
				case 'create_slider':
					/**
					 * 1. create a blank Slider
					 * 2. create a blank Slide
					 * 3. create a blank Static Slide
					 **/

					$slide_id = false;
					$slider_id = $slider->create_blank_slider();
					if($slider_id !== false){
						$slide_id = $slide->create_slide($slider_id); //normal slide
						$slide->create_slide($slider_id, '', true); //static slide
					}

					if($slide_id !== false){
						$this->ajax_response_data(array('slide_id' => $slide_id, 'slider_id' => $slider_id));
					}else{
						$this->ajax_response_error(__('Could not create Slider', 'revslider'));
					}
				break;
				case 'get_addon_list':
					$addon = new RevSliderAddons();
					$addons = $addon->get_addon_list();

					$this->ajax_response_data(array('addons' => $addons));
				break;
				case 'get_layers_by_slide':
					$slide_id = $this->get_val($data, 'slide_id');

					$slide->init_by_id($slide_id);
					$layers = $slide->get_layers();

					$this->ajax_response_data(array('layers' => $layers));
				break;
				case 'activate_addon':
					$handle = $this->get_val($data, 'addon');
					$update = $this->get_val($data, 'update', false);
					$addon = new RevSliderAddons();

					$return = $addon->install_addon($handle, $update);

					if($return === true){
						//return needed files of the plugin somehow
						$data = array();
						$data = apply_filters('revslider_activate_addon', $data, $handle);

						$this->ajax_response_data(array($handle => $data));
					}else{
						$error = ($return === false) ? __('AddOn could not be activated', 'revslider') : $return;
						
						$this->ajax_response_error($error);
					}
				break;
				case 'deactivate_addon':
					$handle = $this->get_val($data, 'addon');
					$addon = new RevSliderAddons();
					$return = $addon->deactivate_addon($handle);

					if($return){
						//return needed files of the plugin somehow
						$this->ajax_response_success(__('AddOn deactivated', 'revslider'));
					}else{
						$this->ajax_response_error(__('AddOn could not be deactivated', 'revslider'));
					}
				break;
				case 'create_draft_page':
					$admin		= new RevSliderFunctionsAdmin();
					$response	= array('open' => false, 'edit' => false);
					$slider_ids = $this->get_val($data, 'slider_ids');
					$modals		= $this->get_val($data, 'modals', array());
					$page_id	= $admin->create_slider_page($slider_ids, $modals);
					
					if($page_id > 0){
						$response['open'] = get_permalink($page_id);
						$response['edit'] = get_edit_post_link($page_id);
					}
					$this->ajax_response_data($response);
				break;
				case 'generate_attachment_metadata':
					$this->generate_attachment_metadata();
					$this->ajax_response_success('');
				break;
				case 'export_layer_group': //developer function only :)
					$title = $this->get_val($data, 'title', $this->get_request_var('title'));
					$videoid = intval($this->get_val($data, 'videoid', $this->get_request_var('videoid')));
					$thumbid = intval($this->get_val($data, 'thumbid', $this->get_request_var('thumbid')));
					$layers = $this->get_val($data, 'layers', $this->get_request_var('layers'));

					$export = new RevSliderSliderExport($title);
					$url = $export->export_layer_group($videoid, $thumbid, $layers);

					$this->ajax_response_data(array('url' => $url));
				break;
				case 'silent_slider_update':
					$upd = new RevSliderPluginUpdate();
					$return = $upd->upgrade_next_slider();

					$this->ajax_response_data($return);
				break;
				case 'load_library_image':
					$images	= (!is_array($data)) ? (array)$data : $data;
					$images	= RevSliderFunctions::esc_attr_deep($images);
					$images	= RevSliderAdmin::esc_js_deep($images);
					$img_data = array();
					
					if(!empty($images)){
						$templates = new RevSliderTemplate();
						$obj = new RevSliderObjectLibrary();
						
						foreach($images as $image){
							$type = $this->get_val($image, 'librarytype');
							$img = $this->get_val($image, 'id');
							$ind = $this->get_val($image, 'ind');
							$mt = $this->get_val($image, 'mediatype');
							switch($type){
								case 'moduletemplates':
								case 'moduletemplateslides':
									$img = $templates->_check_file_path($img, true);
									$img_data[] = array(
										'ind' => $ind,
										'url' => $img,
										'mediatype' => $mt
									);
								break;
								case 'image':
								case 'images':
								case 'layers':
								case 'objects':
									$get = ($mt === 'video') ? 'video_thumb' : 'thumb';
									$img = $obj->_get_object_thumb($img, $get, true);
									if($this->get_val($img, 'error', false) === false){
										$img_data[] = array(
											'ind' => $ind,
											'url' => $this->get_val($img, 'url'),
											'mediatype' => $mt
										);
									}
								break;
								case 'videos':
									$get = ($mt === 'img') ? 'video' : 'video_thumb';
									$img = $obj->_get_object_thumb($img, $get, true);
									if($this->get_val($img, 'error', false) === false){
										$img_data[] = array(
											'ind' => $ind,
											'url' => $this->get_val($img, 'url'),
											'mediatype' => $mt
										);
									}
								break;
							}
						}
					}
					
					$this->ajax_response_data(array('data' => $img_data));
				break;
				case 'get_help_directory':
					include_once(RS_PLUGIN_PATH . 'admin/includes/help.class.php');

					if(class_exists('RevSliderHelp')){
						$help_data = RevSliderHelp::getIndex();
						$this->ajax_response_data(array('data' => $help_data));
					}else{
						$return = '';
					}
				break;
				case 'get_tooltips':
					include_once(RS_PLUGIN_PATH . 'admin/includes/tooltips.class.php');

					if(class_exists('RevSliderTooltips')){
						$tooltips = RevSliderTooltips::getTooltips();
						$this->ajax_response_data(array('data' => $tooltips));
					}else{
						$return = '';
					}
				break;
				case 'set_tooltip_preference':
					update_option('revslider_hide_tooltips', true);
					$return = 'Preference Updated';
				break;
				case 'save_color_preset':
					$presets = $this->get_val($data, 'presets', array());
					$color_presets = RSColorpicker::save_color_presets($presets);
					$this->ajax_response_data(array('presets' => $color_presets));
				break;
				case 'get_facebook_photosets':
					if(!empty($data['url'])){
						$facebook = new RevSliderFacebook();
						$return = $facebook->get_photo_set_photos_options($data['url'], $data['album'], $data['app_id']);
						
						if(empty($return)){
							$error = __('Could not fetch Facebook albums', 'revslider');
							$this->ajax_response_error($error);	
						}
						else {
							if( !isset( $return[0] ) || $return[0] != "error" ) {
								$this->ajax_response_success(__('Successfully fetched Facebook albums', 'revslider'), array('html' => implode(' ', $return)));
							}
							else {
								$error = $return[1];
								$this->ajax_response_error($error);	
							}
						}
						
						/*
						if(!empty($return) && ( isset($return[0]) ) ){
							$this->ajax_response_success(__('Successfully fetched Facebook albums', 'revslider'), array('html' => implode(' ', $return)));
						}else{
							$error = __('Could not fetch Facebook albums', 'revslider');
							$this->ajax_response_error($error);	
						}*/
					}else{
						$this->ajax_response_success(__('Cleared Albums', 'revslider'), array('html' => implode(' ', $return)));
					}
				break;
				case 'get_flickr_photosets':
					$error = __('Could not fetch flickr photosets', 'revslider');
					if(!empty($data['url']) && !empty($data['key'])){
						$flickr = new RevSliderFlickr($data['key']);
						$user_id = $flickr->get_user_from_url($data['url']);
						$return = $flickr->get_photo_sets($user_id, $data['count'], $data['set']);
						if(!empty($return)){
							$this->ajax_response_success(__('Successfully fetched flickr photosets', 'revslider'), array('data' => array('html' => implode(' ', $return))));
						}else{
							$error = __('Could not fetch flickr photosets', 'revslider');
						}
					}else{
						if(empty($data['url']) && empty($data['key'])){
							$this->ajax_response_success(__('Cleared Photosets', 'revslider'), array('html' => implode(' ', $return)));
						}elseif(empty($data['url'])){
							$error = __('No User URL - Could not fetch flickr photosets', 'revslider');
						}else{
							$error = __('No API KEY - Could not fetch flickr photosets', 'revslider');
						}
					}
					
					$this->ajax_response_error($error);
				break;
				case 'get_youtube_playlists':
					if(!empty($data['id'])){
						$youtube = new RevSliderYoutube(trim($data['api']), trim($data['id']));
						$return = $youtube->get_playlist_options($data['playlist']);
						$this->ajax_response_success(__('Successfully fetched YouTube playlists', 'revslider'), array('data' => array('html' => implode(' ', $return))));
					}else{
						$this->ajax_response_error(__('Could not fetch YouTube playlists', 'revslider'));
					}
				break;
				case 'fix_database_issues':
					update_option('revslider_table_version', '1.0.0');
					
					RevSliderFront::create_tables(true);
					
					$this->ajax_response_success(__('Slider Revolution database structure was updated', 'revslider'));
				break;
				case 'trigger_font_deletion':
					$this->delete_google_fonts();
					
					$this->ajax_response_success(__('Downloaded Google Fonts will be updated', 'revslider'));
				break;
				case 'get_v5_slider_list':
					$admin = new RevSliderFunctionsAdmin();
					$sliders = $admin->get_v5_slider_data();
					
					$this->ajax_response_data(array('slider' => $sliders));
				break;
				case 'reimport_v5_slider':
					$status = false;
					if(!empty($data['id'])){
						$admin = new RevSliderFunctionsAdmin();
						$status = $admin->reimport_v5_slider($data['id']);
					}
					if($status === false){
						$this->ajax_response_error(__('Slider could not be transfered to v6', 'revslider'));
					}else{
						$this->ajax_response_success(__('Slider transfered to v6', 'revslider'));
					}
				break;
				default:
					$return = ''; //''is not allowed to be added directly in apply_filters(), so its needed like this
					$return = apply_filters('revslider_do_ajax', $return, $action, $data);
					if($return){
						if(is_array($return)){
							//if(isset($return['message'])) $this->ajax_response_success($return["message"]);
							if(isset($return['message'])){
								$this->ajax_response_data(array('message' => $return['message'], 'data' => $return['data']));
							}

							$this->ajax_response_data(array('data' => $return['data']));
						}else{
							$this->ajax_response_success($return);
						}
					}else{
						$return = '';
					}
				break;
			}
		}catch(Exception $e){
			$message = $e->getMessage();
			if(in_array($action, array('preview_slide', 'preview_slider'))){
				echo $message;
				wp_die();
			}
			$this->ajax_response_error($message);
		}

		//it's an ajax action, so exit
		$this->ajax_response_error(__('No response on action', 'revslider'));
		wp_die();
	}

	/**
	 * Ajax handling for frontend, no privileges here
	 */
	public function do_front_ajax_action(){
		$token = $this->get_post_var('token', false);

		//verify the token
		$is_verified = wp_verify_nonce($token, 'RevSlider_Front');

		$error = false;
		if($is_verified){
			$data = $this->get_post_var('data', false);
			switch($this->get_post_var('client_action', false)){
				case 'get_slider_html':
					$alias = $this->get_post_var('alias', '');
					$usage = $this->get_post_var('usage', '');
					$id = intval($this->get_post_var('id', 0));
					
					//check if $alias exists in database, transform it to id
					if($alias !== ''){
						$sr = new RevSliderSlider();
						$id = intval($sr->alias_exists($alias, true));
					}
					
					if($id > 0){
						$html = '';
						ob_start();
						$slider = new RevSliderOutput();
						$slider->set_ajax_loaded();
						
						$slider_class = $slider->add_slider_to_stage($id, $usage);
						$html = ob_get_contents();
						ob_clean();
						ob_end_clean();
						
						$result = (!empty($slider_class) && $html !== '') ? true : false;
						
						if(!$result){
							$error = __('Slider not found', 'revslider');
						}else{
							if($html !== false){
								$this->ajax_response_data($html);
							}else{
								$error = __('Slider not found', 'revslider');
							}
						}
					}else{
						$error = __('No Data Received', 'revslider');
					}
				break;
			}
		}else{
			$error = true;
		}

		if($error !== false){
			$show_error = ($error !== true) ? __('Loading Error', 'revslider') : __('Loading Error: ', 'revslider') . $error;

			$this->ajax_response_error($show_error, false);
		}
		exit;
	}

	/**
	 * echo json ajax response as error
	 * @before: RevSliderBaseAdmin::ajaxResponseError();
	 */
	protected function ajax_response_error($message, $data = null){
		$this->ajax_response(false, $message, $data, true);
	}

	/**
	 * echo ajax success response with redirect instructions
	 * @before: RevSliderBaseAdmin::ajaxResponseSuccessRedirect();
	 */
	protected function ajax_response_redirect($message, $url){
		$data = array('is_redirect' => true, 'redirect_url' => $url);

		$this->ajax_response(true, $message, $data, true);
	}

	/**
	 * echo json ajax response, without message, only data
	 * @before: RevSliderBaseAdmin::ajaxResponseData()
	 */
	protected function ajax_response_data($data){
		$data = (gettype($data) == 'string') ? array('data' => $data) : $data;

		$this->ajax_response(true, '', $data);
	}

	/**
	 * echo ajax success response
	 * @before: RevSliderBaseAdmin::ajaxResponseSuccess();
	 */
	protected function ajax_response_success($message, $data = null){

		$this->ajax_response(true, $message, $data, true);
	}

	/**
	 * echo json ajax response
	 * before: RevSliderBaseAdmin::ajaxResponse
	 */
	private function ajax_response($success, $message, $data = null){

		$response = array(
			'success' => $success,
			'message' => $message,
		);

		if(!empty($data)){
			if(gettype($data) == 'string'){
				$data = array('data' => $data);
			}

			$response = array_merge($response, $data);
		}

		echo json_encode($response);

		wp_die();
	}

	/**
	 * Create Multilanguage for JavaScript
	 */
	public function get_javascript_multilanguage(){
		$lang = array(
			'please_wait_a_moment' => __('Please Wait a Moment', 'revslider'),
			'oppps' => __('Ooppps....', 'revslider'),
			'no_nav_changes_done' => __('None of the Settings changed. There is Nothing to Save', 'revslider'),
			'no_preset_name' => __('Enter Preset Name to Save or Delete', 'revslider'),
			'customlayergrid_size_title' => __('Custom Size is currently Disabled', 'revslider'),
			'customlayergrid_size_content' => __('The Current Size is set to calculate the Layer grid sizes Automatically.<br>Do you want to continue with Custom Sizes or do you want to keep the Automatically generated sizes ?', 'revslider'),
			'customlayergrid_answer_a' => __('Keep Auto Sizes', 'revslider'),
			'customlayergrid_answer_b' => __('Use Custom Sizes', 'revslider'),
			'removinglayer_title' => __('What should happen Next?', 'revslider'),
			'removinglayer_attention' => __('Need Attention by removing', 'revslider'),
			'removinglayer_content' => __('Where do you want to move the Inherited Layers?', 'revslider'),
			'dragAndDropFile' => __('Drag & Drop Import File', 'revslider'),
			'or' => __('or', 'revslider'),
			'clickToChoose' => __('Click to Choose', 'revslider'),
			'embed' => __('Embed', 'revslider'),
			'export' => __('Export', 'revslider'),
			'delete' => __('Delete', 'revslider'),
			'duplicate' => __('Duplicate', 'revslider'),
			'preview' => __('Preview', 'revslider'),
			'tags' => __('Tags', 'revslider'),
			'folders' => __('Folder', 'revslider'),
			'rename' => __('Rename', 'revslider'),
			'root' => __('Root Level', 'revslider'),
			'simproot' => __('Root', 'revslider'),
			'show' => __('Show', 'revslider'),
			'perpage' => __('Per Page', 'revslider'),
			'convertedlayer' => __('Layer converted Successfully', 'revslider'),
			'layerloopdisabledduetimeline' => __('Layer Loop Effect disabled', 'revslider'),
			'layerbleedsout' => __('<b>Layer width bleeds out of Grid:</b><br>-Auto Layer width has been removed<br>-Line Break set to Content Based', 'revslider'),
			'noMultipleSelectionOfLayers' => __('Multiple Layerselection not Supported<br>in Animation Mode', 'revslider'),
			'closeNews' => __('Close News', 'revslider'),
			'copyrightandlicenseinfo' => __('&copy; Copyright & License Info', 'revslider'),
			'registered' => __('Registered', 'revslider'),
			'notRegisteredNow' => __('Unregistered', 'revslider'),
			'dismissmessages' => __('Dismiss Messages', 'revslider'),
			'someAddonnewVersionAvailable' => __('Some AddOns have new versions available', 'revslider'),
			'newVersionAvailable' => __('New Version Available. Please Update', 'revslider'),
			'addonsmustbeupdated' => __('AddOns Outdated. Please Update', 'revslider'),
			'notRegistered' => __('Plugin is not Registered', 'revslider'),
			'notRegNoPremium' => __('Register to unlock Premium Features', 'revslider'),
			'notRegNoAll' => __('Register to Unlock all Features', 'revslider'),
			'notRegNoAddOns' => __('Register to unlock AddOns', 'revslider'),
			'notRegNoSupport' => __('Register to unlock Support', 'revslider'),
			'notRegNoLibrary' => __('Register to unlock Library', 'revslider'),
			'notRegNoUpdates' => __('Register to unlock Updates', 'revslider'),
			'notRegNoTemplates' => __('Register to unlock Templates', 'revslider'),
			'areyousureupdateplugin' => __('Do you want to start the Update process?', 'revslider'),
			'updatenow' => __('Update Now', 'revslider'),
			'toplevels' => __('Higher Level', 'revslider'),
			'siblings' => __('Current Level', 'revslider'),
			'otherfolders' => __('Other Folders', 'revslider'),
			'parent' => __('Parent Level', 'revslider'),
			'from' => __('from', 'revslider'),
			'to' => __('to', 'revslider'),
			'actionneeded' => __('Action Needed', 'revslider'),
			'updatedoneexist' => __('Done', 'revslider'),
			'updateallnow' => __('Update All', 'revslider'),
			'updatelater' => __('Update Later', 'revslider'),
			'addonsupdatemain' => __('The following AddOns require an update:', 'revslider'),
			'addonsupdatetitle' => __('AddOns need attention', 'revslider'),
			'updatepluginfailed' => __('Updating Plugin Failed', 'revslider'),
			'updatingplugin' => __('Updating Plugin...', 'revslider'),
			'licenseissue' => __('License validation issue Occured. Please contact our Support.', 'revslider'),
			'leave' => __('Back to Overview', 'revslider'),
			'reLoading' => __('Page is reloading...', 'revslider'),
			'updateplugin' => __('Update Plugin', 'revslider'),
			'updatepluginsuccess' => __('Slider Revolution Plugin updated Successfully.', 'revslider'),
			'updatepluginfailure' => __('Slider Revolution Plugin updated Failure:', 'revslider'),
			'updatepluginsuccesssubtext' => __('Slider Revolution Plugin updated Successfully to', 'revslider'),
			'reloadpage' => __('Reload Page', 'revslider'),
			'loading' => __('Loading', 'revslider'),
			'elements' => __('Elements', 'revslider'),
			'loadingthumbs' => __('Loading Thumbnails...', 'revslider'),
			'jquerytriggered' => __('jQuery Triggered', 'revslider'),
			'atriggered' => __('&lt;a&gt; Tag Link', 'revslider'),
			'firstslide' => __('First Slide', 'revslider'),
			'lastslide' => __('Last Slide', 'revslider'),
			'nextslide' => __('Next Slide', 'revslider'),
			'previousslide' => __('Previous Slide', 'revslider'),
			'somesourceisnotcorrect' => __('Some Settings in Slider <strong>Source may not complete</strong>.<br>Please Complete All Settings in Slider Sources.', 'revslider'),
			'somelayerslocked' => __('Some Layers are <strong>Locked</strong> and/or <strong>Invisible</strong>.<br>Change Status in Timeline.', 'revslider'),
			'editorisLoading' => __('Editor is Loading...', 'revslider'),
			'addingnewblankmodule' => __('Adding new Blank Module...', 'revslider'),
			'opening' => __('Opening', 'revslider'),
			'featuredimages' => __('Featured Images', 'revslider'),
			'images' => __('Images', 'revslider'),
			'none' => __('None', 'revslider'),
			'select' => __('Select', 'revslider'),
			'reset' => __('Reset', 'revslider'),
			'custom' => __('Custom', 'revslider'),
			'out' => __('OUT', 'revslider'),
			'in' => __('IN', 'revslider'),
			'sticky_navigation' => __('Navigation Options', 'revslider'),
			'sticky_slider' => __('Module General Options', 'revslider'),
			'sticky_slide' => __('Slide Options', 'revslider'),
			'sticky_layer' => __('Layer Options', 'revslider'),
			'imageCouldNotBeLoaded' => __('Set a Slide Background Image to use this feature', 'revslider'),
			'oppps' => __('Ooppps....', 'revslider'),
			'no_nav_changes_done' => __('None of the Settings changed. There is Nothing to Save', 'revslider'),
			'no_preset_name' => __('Enter Preset Name to Save or Delete', 'revslider'),
			'customlayergrid_size_title' => __('Custom Size is currently Disabled', 'revslider'),
			'customlayergrid_size_content' => __('The Current Size is set to calculate the Layer grid sizes Automatically.<br>Do you want to continue with Custom Sizes or do you want to keep the Automatically generated sizes ?', 'revslider'),
			'customlayergrid_answer_a' => __('Keep Auto Sizes', 'revslider'),
			'customlayergrid_answer_b' => __('Use Custom Sizes', 'revslider'),
			'removinglayer_title' => __('What should happen Next?', 'revslider'),
			'removinglayer_attention' => __('Need Attention by removing', 'revslider'),
			'removinglayer_content' => __('Where do you want to move the Inherited Layers?', 'revslider'),
			'dragAndDropFile' => __('Drag & Drop Import File', 'revslider'),
			'or' => __('or', 'revslider'),
			'clickToChoose' => __('Click to Choose', 'revslider'),
			'embed' => __('Embed', 'revslider'),
			'export' => __('Export', 'revslider'),
			'exporthtml' => __('HTML', 'revslider'),
			'delete' => __('Delete', 'revslider'),
			'duplicate' => __('Duplicate', 'revslider'),
			'preview' => __('Preview', 'revslider'),
			'tags' => __('Tags', 'revslider'),
			'folders' => __('Folder', 'revslider'),
			'rename' => __('Rename', 'revslider'),
			'root' => __('Root Level', 'revslider'),
			'simproot' => __('Root', 'revslider'),
			'show' => __('Show', 'revslider'),
			'perpage' => __('Per Page', 'revslider'),
			'releaseToUpload' => __('Release to Upload file', 'revslider'),
			'moduleZipFile' => __('Module .zip', 'revslider'),
			'importing' => __('Processing Import of', 'revslider'),
			'importfailure' => __('An Error Occured while importing', 'revslider'),
			'successImportFile' => __('File Succesfully Imported', 'revslider'),
			'importReport' => __('Import Report', 'revslider'),
			'updateNow' => __('Update Now', 'revslider'),
			'activateToUpdate' => __('Activate To Update', 'revslider'),
			'activated' => __('Activated', 'revslider'),
			'notActivated' => __('Not Activated', 'revslider'),
			'registerCode' => __('Register this Code', 'revslider'),
			'deregisterCode' => __('Deregister this Code', 'revslider'),
			'embedingLine1' => __('Standard Module Embedding', 'revslider'),
			'embedingLine2' => __('For the <b>pages and posts</b> editor insert the Shortcode:', 'revslider'),
			'embedingLine2a' => __('To Use it as <b>Modal</b> on <b>pages and posts</b> editor insert the Shortcode:', 'revslider'),
			'embedingLine3' => __('From the <b>widgets panel</b> drag the "Revolution Module" widget to the desired sidebar.', 'revslider'),
			'embedingLine4' => __('Advanced Module Embedding', 'revslider'),
			'embedingLine5' => __('For the <b>theme html</b> use:', 'revslider'),
			'embedingLine6' => __('To add the slider only to the homepage, use:', 'revslider'),
			'embedingLine7' => __('To add the slider only to single Pages, use:', 'revslider'),
			'noLayersSelected' => __('Select a Layer', 'revslider'),
			'layeraction_group_link' => __('Link Actions', 'revslider'),
			'layeraction_group_slide' => __('Slide Actions', 'revslider'),
			'layeraction_group_layer' => __('Layer Actions', 'revslider'),
			'layeraction_group_media' => __('Media Actions', 'revslider'),
			'layeraction_group_fullscreen' => __('Fullscreen Actions', 'revslider'),
			'layeraction_group_advanced' => __('Advanced Actions', 'revslider'),
			'layeraction_link' => __('Simple Link', 'revslider'),
			'layeraction_callback' => __('Call Back', 'revslider'),
			'layeraction_modal' => __('Open Slider Modal', 'revslider'),
			'layeraction_scroll_under' => __('Scroll below Slider', 'revslider'),
			'layeraction_scrollto' => __('Scroll To ID', 'revslider'),
			'layeraction_jumpto' => __('Jump to Slide', 'revslider'),
			'layeraction_next' => __('Next Slide', 'revslider'),
			'layeraction_prev' => __('Previous Slide', 'revslider'),
			'layeraction_next_frame' => __('Next Frame', 'revslider'),
			'layeraction_prev_frame' => __('Previous Frame', 'revslider'),
			'layeraction_pause' => __('Pause Slider', 'revslider'),
			'layeraction_resume' => __('Play Slide', 'revslider'),
			'layeraction_close_modal' => __('Close Slider Modal', 'revslider'),
			'layeraction_open_modal' => __('Open Slider Modal', 'revslider'),
			'layeraction_toggle_slider' => __('Toggle Slider', 'revslider'),
			'layeraction_start_in' => __('Go to 1st Frame ', 'revslider'),
			'layeraction_start_out' => __('Go to Last Frame', 'revslider'),
			'layeraction_start_frame' => __('Go to Frame "N"', 'revslider'),
			'layeraction_toggle_layer' => __('Toggle 1st / Last Frame', 'revslider'),
			'layeraction_toggle_frames' => __('Toggle "N/M" Frames', 'revslider'),
			'layeraction_start_video' => __('Start Media', 'revslider'),
			'layeraction_stop_video' => __('Stop Media', 'revslider'),
			'layeraction_toggle_video' => __('Toggle Media', 'revslider'),
			'layeraction_mute_video' => __('Mute Media', 'revslider'),
			'layeraction_unmute_video' => __('Unmute Media', 'revslider'),
			'layeraction_toggle_mute_video' => __('Toggle Mute Media', 'revslider'),
			'layeraction_toggle_global_mute_video' => __('Toggle Mute All Media', 'revslider'),
			'layeraction_togglefullscreen' => __('Toggle Fullscreen', 'revslider'),
			'layeraction_gofullscreen' => __('Enter Fullscreen', 'revslider'),
			'layeraction_exitfullscreen' => __('Exit Fullscreen', 'revslider'),
			'layeraction_simulate_click' => __('Simulate Click', 'revslider'),
			'layeraction_toggle_class' => __('Toggle Class', 'revslider'),
			'layeraction_none' => __('Disabled', 'revslider'),
			'backgroundvideo' => __('Background Video', 'revslider'),
			'videoactiveslide' => __('Video in Active Slide', 'revslider'),
			'firstvideo' => __('Video in Active Slide', 'revslider'),
			'triggeredby' => __('Behavior', 'revslider'),
			'addaction' => __('Add Action to ', 'revslider'),
			'ol_images' => __('Images', 'revslider'),
			'ol_layers' => __('Layer Objects', 'revslider'),
			'ol_objects' => __('Objects', 'revslider'),
			'ol_modules' => __('Own Modules', 'revslider'),
			'ol_fonticons' => __('Font Icons', 'revslider'),
			'ol_moduletemplates' => __('Module Templates', 'revslider'),
			'ol_videos' => __('Videos', 'revslider'),
			'ol_svgs' => __('SVG\'s', 'revslider'),
			'ol_favorite' => __('Favorites', 'revslider'),
			'installed' => __('Installed', 'revslider'),
			'notinstalled' => __('Not Installed', 'revslider'),
			'setupnotes' => __('Setup Notes', 'revslider'),
			'requirements' => __('Requirements', 'revslider'),
			'installedversion' => __('Installed Version', 'revslider'),
			'cantpulllinebreakoutside' => __('Use LineBreaks only in Columns', 'revslider'),
			'availableversion' => __('Available Version', 'revslider'),
			'installpackage' => __('Installing Template Package', 'revslider'),
			'installtemplate' => __('Install Template', 'revslider'),
			'installingtemplate' => __('Installing Template', 'revslider'),
			'search' => __('Search', 'revslider'),
			'publish' => __('Publish', 'revslider'),
			'unpublish' => __('Unpublish', 'revslider'),
			'folderBIG' => __('FOLDER', 'revslider'),
			'moduleBIG' => __('MODULE', 'revslider'),
			'objectBIG' => __('OBJECT', 'revslider'),
			'packageBIG' => __('PACKAGE', 'revslider'),
			'thumbnail' => __('Thumbnail', 'revslider'),
			'imageBIG' => __('IMAGE', 'revslider'),
			'videoBIG' => __('VIDEO', 'revslider'),
			'iconBIG' => __('ICON', 'revslider'),
			'svgBIG' => __('SVG', 'revslider'),
			'fontBIG' => __('FONT', 'revslider'),
			'redownloadTemplate' => __('Re-Download Online', 'revslider'),
			'createBlankPage' => __('Create Blank Page', 'revslider'),
			'please_wait_a_moment' => __('Please Wait a Moment', 'revslider'),
			'changingscreensize' => __('Changing Screen Size', 'revslider'),
			'qs_headlines' => __('Headlines', 'revslider'),
			'qs_content' => __('Content', 'revslider'),
			'qs_buttons' => __('Buttons', 'revslider'),
			'qs_bgspace' => __('BG & Space', 'revslider'),
			'qs_shadow' => __('Shadow', 'revslider'),
			'qs_shadows' => __('Shadow', 'revslider'),
			'saveslide' => __('Saving Slide', 'revslider'),
			'loadconfig' => __('Loading Configuration', 'revslider'),
			'updateselects' => __('Updating Lists', 'revslider'),
			'lastslide' => __('Last Slide', 'revslider'),
			'globalLayers' => __('Global Layers', 'revslider'),
			'slidersettings' => __('Slider Settings', 'revslider'),
			'animatefrom' => __('Animate From', 'revslider'),
			'animateto' => __('Keyframe #', 'revslider'),
			'transformidle' => __('Transform Idle', 'revslider'),
			'enterstage' => __('Anim From', 'revslider'),
			'leavestage' => __('Anim To', 'revslider'),
			'onstage' => __('Anim To', 'revslider'),	
			'keyframe' => __('Keyframe', 'revslider'),
			'notenoughspaceontimeline' => __('Not Enough space between Frames.', 'revslider'),
			'framesizecannotbeextended' => __('Frame Size can not be Extended. Not enough Space.', 'revslider'),
			'backupTemplateLoop' => __('Loop Template', 'revslider'),
			'backupTemplateLayerAnim' => __('Animation Template', 'revslider'),
			'choose_image' => __('Choose Image', 'revslider'),
			'choose_video' => __('Choose Video', 'revslider'),
			'slider_revolution_shortcode_creator' => __('Slider Revolution Shortcode Creator', 'revslider'),
			'shortcode_generator' => __('Shortcode Generator', 'revslider'),
			'please_add_at_least_one_layer' => __('Please add at least one Layer.', 'revslider'),
			'shortcode_parsing_successfull' => __('Shortcode parsing successfull. Items can be found in step 3', 'revslider'),
			'shortcode_could_not_be_correctly_parsed' => __('Shortcode could not be parsed.', 'revslider'),
			'addonrequired' => __('Addon Required', 'revslider'),
			'licencerequired' => __('Activate License', 'revslider'),
			'searcforicon' => __('Search Icons...', 'revslider'),
			'savecurrenttemplate' => __('Save Current Template', 'revslider'),
			'overwritetemplate' => __('Overwrite Template ?', 'revslider'),
			'deletetemplate' => __('Delete Template ?', 'revslider'),
			'credits' => __('Credits', 'revslider'),
			'notinstalled' => __('Not Installed', 'revslider'),
			'enabled' => __('Enabled', 'revslider'),
			'global' => __('Global', 'revslider'),
			'install_and_activate' => __('Install Add-On', 'revslider'),
			'install' => __('Install', 'revslider'),
			'enableaddon' => __('Enable Add-On', 'revslider'),
			'disableaddon' => __('Disable Add-On', 'revslider'),
			'enableglobaladdon' => __('Enable Global Add-On', 'revslider'),
			'disableglobaladdon' => __('Disable Global Add-On', 'revslider'),
			'sliderrevversion' => __('Slider Revolution Version', 'revslider'),
			'checkforrequirements' => __('Check Requirements', 'revslider'),
			'activateglobaladdon' => __('Activate Global Add-On', 'revslider'),
			'activateaddon' => __('Activate Add-On', 'revslider'),
			'activatingaddon' => __('Activating Add-On', 'revslider'),
			'enablingaddon' => __('Enabling Add-On', 'revslider'),
			'addon' => __('Add-On', 'revslider'),
			'installingaddon' => __('Installing Add-On', 'revslider'),
			'disablingaddon' => __('Disabling Add-On', 'revslider'),
			'buildingSelects' => __('Building Select Boxes', 'revslider'),
			'warning' => __('Warning', 'revslider'),
			'blank_page_added' => __('Blank Page Created', 'revslider'),
			'blank_page_created' => __('Blank page has been created:', 'revslider'),
			'visit_page' => __('Visit Page', 'revslider'),
			'edit_page' => __('Edit Page', 'revslider'),
			'closeandstay' => __('Close', 'revslider'),
			'changesneedreload' => __('The changes you made require a page reload!', 'revslider'),
			'saveprojectornot ' => __('Save your project & reload the page or cancel', 'revslider'),
			'saveandreload' => __('Save & Reload', 'revslider'),
			'canceldontreload' => __('Cancel & Reload Later', 'revslider'),
			'saveconfig' => __('Save Configuration', 'revslider'),
			'updatingaddon' => __('Updating', 'revslider'),
			'addonOnlyInSlider' => __('Enable/Disable Add-On on Module', 'revslider'),
			'sortbycreation' => __('Sort by Creation', 'revslider'),
			'creationascending' => __('Creation Ascending', 'revslider'),
			'sortbytitle' => __('Sort by Title', 'revslider'),
			'titledescending' => __('Title Descending', 'revslider'),
			'updatefromserver' => __('Update List', 'revslider'),
			'audiolibraryloading' => __('Audio Wave Library is Loading...', 'revslider'),
			'editModule' => __('Edit Module', 'revslider'),
			'editSlide' => __('Edit Slide', 'revslider'),
			'showSlides' => __('Show Slides', 'revslider'),
			'openInEditor' => __('Open in Editor', 'revslider'),
			'openFolder' => __('Open Folder', 'revslider'),
			'moveToFolder' => __('Move to Folder', 'revslider'),
			'loadingcodemirror' => __('Loading CodeMirror Library...', 'revslider'),
			'lockunlocklayer' => __('Lock / Unlock Selected', 'revslider'),
			'nrlayersimporting' => __('Layers Importing', 'revslider'),
			'nothingselected' => __('Nothing Selected', 'revslider'),
			'layerwithaction' => __('Layer with Action', 'revslider'),
			'imageisloading' => __('Image is Loading...', 'revslider'),
			'importinglayers' => __('Importing Layers...', 'revslider'),
			'triggeredby' => __('Triggered By', 'revslider'),
			'import' => __('Imported', 'revslider'),
			'layersBIG' => __('LAYERS', 'revslider'),
			'intinheriting' => __('Responsivity', 'revslider'),
			'changesdone_exit' => __('The changes you made will be lost!', 'revslider'),
			'exitwihoutchangesornot' => __('Are you sure you want to continue?', 'revslider'),
			'areyousuretoexport' => __('Are you sure you want to export ', 'revslider'),
			'areyousuretodelete' => __('Are you sure you want to delete ', 'revslider'),
			'areyousuretodeleteeverything' => __('Delete All Sliders and Folders included in ', 'revslider'),
			'leavewithoutsave' => __('Leave without Save', 'revslider'), 
			'updatingtakes' => __('Updating the Plugin may take a few moments.', 'revslider'),
			'exportslidertxt' => __('Downloading the Zip File may take a few moments.', 'revslider'),
			'exportslider' => __('Export Slider', 'revslider'),
			'yesexport' => __('Yes, Export Slider', 'revslider'),
			'yesdelete' => __('Yes, Delete Slider', 'revslider'),
			'yesdeleteslide' => __('Yes, Delete Slide', 'revslider'),
			'yesdeleteall' => __('Yes, Delete All Slider(s)', 'revslider'),
			'stayineditor' => __('Stay in Edior', 'revslider'),
			'redirectingtooverview' => __('Redirecting to Overview Page', 'revslider'),
			'leavingpage' => __('Leaving current Page', 'revslider'),
			'ashtmlexport' => __('as HTML Document', 'revslider'),
			'preparingdatas' => __('Preparing Data...', 'revslider'),
			'loadingcontent' => __('Loading Content...', 'revslider'),
			'copy' => __('Copy', 'revslider'),
			'paste' => __('Paste', 'revslider'),
			'framewait' => __('WAIT', 'revslider'),
			'frstframe' => __('1st Frame', 'revslider'),
			'lastframe' => __('Last Frame', 'revslider'),
			'onlyonaction' => __('on Action', 'revslider'),
			'cannotbeundone' => __('This action can not be undone !!', 'revslider'),
			'deleteslider' => __('Delete Slider', 'revslider'),
			'deleteslide' => __('Delete Slide', 'revslider'),
			'deletingslide' => __('This can be Undone only within the Current session.', 'revslider'),
			'deleteselectedslide' => __('Are you sure you want to delete the selected Slide:', 'revslider'),
			'cancel' => __('Cancel', 'revslider'),
			'addons' => __('Add-Ons', 'revslider'),
			'deletingsingleslide' => __('Deleting Slide', 'revslider'),
			'lastslidenodelete' => __('"Last Slide in Module. Can not be deleted"', 'revslider'),
			'deletingslider' => __('Deleting Slider', 'revslider'),
			'active_sr_tmp_obl' => __('Template & Object Library', 'revslider'),
			'active_sr_inst_upd' => __('Instant Updates', 'revslider'),
			'active_sr_one_on_one' => __('1on1 Support', 'revslider'),
			'getlicensekey' => __('Get a Purchase Code', 'revslider'),
			'ihavelicensekey' => __('I have a Purchase Code', 'revslider'),
			'active_sr_to_access' => __('Register Slider Revolution<br>to Unlock Premium Features', 'revslider'),
			'active_sr_plg_activ' => __('Register Purchase Code', 'revslider'),
			'onepurchasekey' => __('1 Purchase Code per Website!', 'revslider'),
			'onepurchasekey_info' => __('If you want to use your purchase code on<br>another domain, please deregister it first or', 'revslider'),
			'parallaxsettoenabled' => __('Parallax is now generally Enabled', 'revslider'),
			'timelinescrollsettoenabled' => __('Scroll Based Timeline is now generally Enabled', 'revslider'),
			'feffectscrollsettoenabled' => __('Filter Effect Scroll is now generally Enabled', 'revslider'),
			'nolayersinslide' => __('Slide has no Layers', 'revslider'),
			'leaving' => __('Changes that you made may not be saved.', 'revslider'),
			'sliderasmodal' => __('Add Slider as Modal', 'revslider'),
			'register_to_unlock' => __('Register to unlock all Premium Features', 'revslider'),
			'premium_features_unlocked' => __('All Premium Features unlocked', 'revslider'),
			'tryagainlater' => __('Please try again later', 'revslider')
		);

		return apply_filters('revslider_get_javascript_multilanguage', $lang);
	}

	
	/**
	 * set the page that should be shown
	 **/
	private function set_current_page(){
		$view = $this->get_get_var('view');
		$this->view = (empty($view)) ? 'sliders' : $this->get_get_var('view');
	}

	/**
	 * include/display the previously set page
	 * only allow certain pages to be showed
	 **/
	public function display_admin_page(){
		try{
			if(!in_array($this->view, $this->allowed_views)){
				$this->throw_error(__('Bad Request', 'revslider'));
			}

			switch ($this->view){
			//switch URLs to corresponding php files
			case 'slide':
				$view = 'builder';
				break;
			case 'sliders':
			default:
				$view = 'overview';
				break;
			}

			$this->validate_filepath($this->path_views . $view . '.php', 'View');

			require $this->path_views . 'header.php';
			require $this->path_views . $view . '.php';
			require $this->path_views . 'footer.php';

		}catch (Exception $e){
			$this->show_error($this->view, $e->getMessage());
		}
	}
	
	
	/**
	 * show an nice designed error
	 **/
	public function show_error($view, $message){
		echo '<div class="rs-error">';
		echo __('Slider Revolution encountered the following error: ', 'revslider');
		echo esc_attr($view);
		echo ' - Error: <span>';
		echo esc_attr($message);
		echo '</span>';
		echo '</div>';
		exit;
	}
	
	
	/**
	 * validate that some file exists, if not - throw error
	 * @before: RevSliderFunctions::validateFilepath
	 */
	public function validate_filepath($filepath, $prefix = null){
		if(file_exists($filepath) == true) return true;
		
		$prefix	 = ($prefix == null) ? 'File' : $prefix;
		$message = $prefix.' '.esc_attr($filepath).' not exists!';
		
		$this->throw_error($message);
	}
	
	
	/**
	 * Activate the Plugin through the ThemePunch Servers
	 * @before: RevSliderOperations::checkPurchaseVerification();
	 **/
	public function activate_plugin($code){
		$rslb = new RevSliderLoadBalancer();
		$data = array(
			'code'		=> urlencode($code),
			'version'	=> urlencode(RS_REVISION),
			'product'	=> urlencode(RS_PLUGIN_SLUG)
		);
		
		$response	  = $rslb->call_url('activate.php', $data, 'updates');
		$version_info = wp_remote_retrieve_body($response);
		
		if(is_wp_error($version_info)){
			return false;
		}
		
		if($version_info == 'valid'){
			update_option('revslider-valid', 'true');
			update_option('revslider-code', $code);
			return true;
		}elseif($version_info == 'exist'){
			return 'exist';
		}elseif($version_info == 'banned'){
			return 'banned';
		}
		
		return false;
	}
	
	
	/**
	 * Deactivate the Plugin through the ThemePunch Servers
	 * @before: RevSliderOperations::doPurchaseDeactivation();
	 **/
	public function deactivate_plugin(){
		$rslb = new RevSliderLoadBalancer();
		$code = get_option('revslider-code', '');
		$data = array(
			'code'		=> urlencode($code),
			'product'	=> urlencode(RS_PLUGIN_SLUG)
		);
		$res = $rslb->call_url('deactivate.php', $data, 'updates');
		$vi	 = wp_remote_retrieve_body($res);
		
		if(is_wp_error($vi)){
			return false;
		}

		if($vi == 'valid'){
			update_option('revslider-valid', 'false');
			update_option('revslider-code', '');
			return true;
		}
		
		return false;
	}
	
	
	/**
	 * Create a temporary fake page/post
	 * @since: 6.0
	 **/
	public function create_fake_post($content, $title = 'Slider Revolution'){
		$post				 = new stdClass();
		$post->ID			 = -1;
		$post->post_author	 = get_current_user_id();
		$post->post_date	 = current_time('mysql');
		$post->post_date_gmt = current_time('mysql', 1);
		$post->post_title	 = $title;
		$post->post_content	 = $content;
		$post->post_status	 = 'publish';
		$post->comment_status = 'closed';
		$post->ping_status	 = 'closed';
		$post->post_name	 = 'rs-fake-page-' . rand(1, 99999); //append random number to avoid clash
		$post->post_type	 = 'page';
		$post->filter		 = 'raw'; //important
		
		//$post->post_meta		= new stdClass();
		//$post->post_meta->_wp_page_template= '../public/views/revslider-page-template.php';
		
		//Convert to WP_Post object
		$wp_post = new WP_Post($post);
		//Add the fake post to the cache
		wp_cache_add(-1, $wp_post, 'posts');
		
		global $wp, $wp_query;

		// Update the main query
		$wp_query->queried_object_id = -1;
		$wp_query->post				 = $wp_post;
		$wp_query->posts			 = array($wp_post);
		$wp_query->queried_object	 = $wp_post;
		$wp_query->found_posts		 = 1;
		$wp_query->post_count		 = 1;
		$wp_query->max_num_pages	 = 1;
		$wp_query->is_page			 = true;
		$wp_query->is_singular		 = true;
		$wp_query->is_single		 = false;
		$wp_query->is_attachment	 = false;
		$wp_query->is_archive		 = false;
		$wp_query->is_category		 = false;
		$wp_query->is_tag			 = false;
		$wp_query->is_tax			 = false;
		$wp_query->is_author		 = false;
		$wp_query->is_date			 = false;
		$wp_query->is_year			 = false;
		$wp_query->is_month			 = false;
		$wp_query->is_day			 = false;
		$wp_query->is_time			 = false;
		$wp_query->is_search		 = false;
		$wp_query->is_feed			 = false;
		$wp_query->is_comment_feed	 = false;
		$wp_query->is_trackback		 = false;
		$wp_query->is_home			 = false;
		$wp_query->is_embed			 = false;
		$wp_query->is_404			 = false;
		$wp_query->is_paged			 = false;
		$wp_query->is_admin			 = false;
		$wp_query->is_preview		 = false;
		$wp_query->is_robots		 = false; 
		$wp_query->is_posts_page	 = false;
		$wp_query->is_post_type_archive	= false;
		
		//Update globals
		$GLOBALS['wp_query'] = $wp_query;
		$wp->register_globals();
		
		return $wp_post;
	}
	
	
	/**
	 * esc attr recursive
	 * @since: 6.0
	 */
	public static function esc_js_deep($value){
		$value = is_array($value) ? array_map(array('RevSliderAdmin', 'esc_js_deep'), $value) : esc_js($value);
		
		return $value;
	}
	
	
	/**
	 * generate missing attachement metadata for images
	 * @since: 6.0
	 **/
	public function generate_attachment_metadata(){
		$rs_meta_create = get_option('rs_image_meta_todo', array());
		if(!empty($rs_meta_create)){
			foreach($rs_meta_create as $attach_id => $save_dir){
				if($attach_data = wp_generate_attachment_metadata($attach_id, $save_dir)){
					wp_update_attachment_metadata($attach_id, $attach_data);
				}
				unset($rs_meta_create[$attach_id]);
				
				update_option('rs_image_meta_todo', $rs_meta_create);
			}
		}
	}

}