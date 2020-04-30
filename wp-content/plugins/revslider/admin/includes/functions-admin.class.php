<?php
/**
 * @author    ThemePunch <info@themepunch.com>
 * @link      https://www.themepunch.com/
 * @copyright 2019 ThemePunch
 */
 
if(!defined('ABSPATH')) exit();

class RevSliderFunctionsAdmin extends RevSliderFunctions {
	
	/**
	 * get the full object of: 
	 * +- Slider Templates
	 * +- Created Slider
	 * +- Object Library Images
	 * - Object Library Videos
	 * +- SVG
	 * +- Font Icons
	 * - layers
	 **/
	public function get_full_library($include = array('all'), $tmp_slide_uid = array(), $refresh_from_server = false, $get_static_slide = false){
		$include	= (array)$include;
		$template	= new RevSliderTemplate();
		$library	= new RevSliderObjectLibrary();
		$slide		= new RevSliderSlide();
		$object		= array();
		$tmp_slide_uid = ($tmp_slide_uid !== false) ? (array)$tmp_slide_uid : array();
		
		if($refresh_from_server){
			if(in_array('all', $include) || in_array('moduletemplates', $include)){ //refresh template list from server
				$template->_get_template_list(true);
				if(!isset($object['moduletemplates'])) $object['moduletemplates'] = array();
				$object['moduletemplates']['tags'] = $template->get_template_categories();
				asort($object['moduletemplates']['tags']);
			}
			if(in_array('all', $include) || in_array('layers', $include)){ //refresh object list from server
				$library->_get_list(true);
				if(!isset($object['layers'])) $object['layers'] = array();
				$object['layers']['tags'] = $library->get_objects_categories('4');
				asort($object['layers']['tags']);
			}
			if(in_array('all', $include) || in_array('videos', $include)){ //refresh object list from server
				$library->_get_list(true);
				if(!isset($object['videos'])) $object['videos'] = array();
				$object['videos']['tags'] = $library->get_objects_categories('3');
				asort($object['videos']['tags']);
			}
			if(in_array('all', $include) || in_array('images', $include)){ //refresh object list from server
				$library->_get_list(true);
				if(!isset($object['images'])) $object['images'] = array();
				$object['images']['tags'] = $library->get_objects_categories('2');
				asort($object['images']['tags']);
			}
			if(in_array('all', $include) || in_array('objects', $include)){ //refresh object list from server
				$library->_get_list(true);
				if(!isset($object['objects'])) $object['objects'] = array();
				$object['objects']['tags'] = $library->get_objects_categories('1');
				asort($object['objects']['tags']);
			}
		}
		
		if(in_array('moduletemplates', $include) || in_array('all', $include)){
			if(!isset($object['moduletemplates'])) $object['moduletemplates'] = array();
			$object['moduletemplates']['items']	= $template->get_tp_template_sliders_for_library();
		}
		if(in_array('moduletemplateslides', $include) || in_array('all', $include)){
			if(!isset($object['moduletemplateslides'])) $object['moduletemplateslides'] = array();
			$object['moduletemplateslides']['items'] = $template->get_tp_template_slides_for_library($tmp_slide_uid);
		}
		if(in_array('modules', $include) || in_array('all', $include)){
			if(!isset($object['modules'])) $object['modules'] = array();
			$object['modules']['items'] = $this->get_slider_overview();
		}
		if(in_array('moduleslides', $include) || in_array('all', $include)){
			if(!isset($object['moduleslides'])) $object['moduleslides'] = array();
			$object['moduleslides']['items'] = $slide->get_slides_for_library($tmp_slide_uid, $get_static_slide);
		}
		if(in_array('svgs', $include) || in_array('all', $include)){
			if(!isset($object['svgs'])) $object['svgs'] = array();
			$object['svgs']['items'] = $library->get_svg_sets_full();
		}
		if(in_array('fonticons', $include) || in_array('all', $include)){
			if(!isset($object['fonticons'])) $object['fonticons'] = array();
			$object['fonticons']['items'] = $library->get_font_icons();
		}
		if(in_array('layers', $include) || in_array('all', $include)){
			if(!isset($object['layers'])) $object['layers'] = array();
			$object['layers']['items'] = $library->load_objects('4');
		}
		if(in_array('videos', $include) || in_array('all', $include)){
			if(!isset($object['videos'])) $object['videos'] = array();
			$object['videos']['items'] = $library->load_objects('3');
		}
		if(in_array('images', $include) || in_array('all', $include)){
			if(!isset($object['images'])) $object['images'] = array();
			$object['images']['items'] = $library->load_objects('2');
		}
		if(in_array('objects', $include) || in_array('all', $include)){
			if(!isset($object['objects'])) $object['objects'] = array();
			$object['objects']['items'] = $library->load_objects('1');
		}
		
		return $object;
	}
	
	
	/**
	 * get the short library with categories and how many elements exist
	 **/
	public function get_short_library(){
		
		$template = new RevSliderTemplate();
		$library = new RevSliderObjectLibrary();
		$sliders = $this->get_slider_overview();
		
		
		$slider_cat = array();
		if(!empty($sliders)){
			foreach($sliders as $slider){
				$tags = $this->get_val($slider, 'tags', array());
				if(!empty($tags)){
					foreach($tags as $tag){
						if(trim($tag) !== '' && !isset($slider_cat[$tag])) $slider_cat[$tag] = ucwords($tag);
					}
				}
			}
		}
		
		$svg_cat = $library->get_svg_categories();
		$oc	= $library->get_objects_categories('1');
		$oc2 = $library->get_objects_categories('2');
		$oc3 = $library->get_objects_categories('3');
		$oc4 = $library->get_objects_categories('4');
		$t_cat = $template->get_template_categories();
		$font_cat = $library->get_font_tags();
		
		asort($oc);
		asort($t_cat);
		asort($slider_cat);
		asort($svg_cat);
		asort($font_cat);
		
		return array(
			'moduletemplates' => array('tags' => $t_cat),
			'modules'	=> array('tags' => $slider_cat),
			'svgs'		=> array('tags' => $svg_cat),
			'fonticons'	=> array('tags' => $font_cat),
			'layers'	=> array('tags' => $oc4),
			'videos'	=> array('tags' => $oc3),
			'images'	=> array('tags' => $oc2),
			'objects'	=> array('tags' => $oc)
		);
	}
	
	
	/**
	 * Get Sliders data for the overview page
	 **/
	public function get_slider_overview(){
		$rs_slider	= new RevSliderSlider();
		$sliders	= $rs_slider->get_sliders(false);

		$rs_folder	= new RevSliderFolder();
		$folders	= $rs_folder->get_folders();
		
		$sliders 	= array_merge($sliders, $folders);
		$data		= array();
		
		if(!empty($sliders)){
			foreach($sliders as $slider){
				$slider->init_layer = false;
				$data[] = $slider->get_overview_data();
			}
		}
		
		return $data;
	}
	
	
	/**
	 * insert custom animations
	 * @before: RevSliderOperations::insertCustomAnim();
	 */
	public function insert_animation($animation, $type){
		$handle = $this->get_val($animation, 'name', false);
		$result = false;
		
		if($handle !== false && trim($handle) !== ''){
			global $wpdb;
			
			//check if handle exists
			$arr = array(
				'handle'	=> $this->get_val($animation, 'name'),
				'params'	=> json_encode($animation),
				'settings'	=> $type
			);
			
			$result = $wpdb->insert($wpdb->prefix . RevSliderFront::TABLE_LAYER_ANIMATIONS, $arr);
		}

		return ($result) ? $wpdb->insert_id : $result;
	}
	
	
	/**
	 * update custom animations
	 * @before: RevSliderOperations::updateCustomAnim();
	 */
	public function update_animation($animation_id, $animation, $type){
		global $wpdb;
		
		$arr = array(
			'handle'	=> $this->get_val($animation, 'name'),
			'params'	=> json_encode($animation),
			'settings'	=> $type
		);
		
		$result = $wpdb->update($wpdb->prefix . RevSliderFront::TABLE_LAYER_ANIMATIONS, $arr, array('id' => $animation_id));
		
		return ($result) ? $animation_id : $result;
	}
	
	
	/**
	 * delete custom animations
	 * @before: RevSliderOperations::deleteCustomAnim();
	 */
	public function delete_animation($animation_id){
		global $wpdb;
		
		$result = $wpdb->delete($wpdb->prefix . RevSliderFront::TABLE_LAYER_ANIMATIONS, array('id' => $animation_id));
		
		return $result;
	}
	
	
	/**
	 * @since: 5.3.0
	 * create a page with revslider shortcodes included
	 * @before: RevSliderOperations::create_slider_page();
	 **/
	public static function create_slider_page($added, $modals = array()){
		global $wp_version;
		
		$new_page_id = 0;
		
		if(!is_array($added)) return apply_filters('revslider_create_slider_page', $new_page_id, $added);
		
		$content = '';
		$page_id = get_option('rs_import_page_id', 1);
		
		//get alias of all new Sliders that got created and add them as a shortcode onto a page
		if(!empty($added)){
			foreach($added as $sid){
				$slider = new RevSliderSlider();
				$slider->init_by_id($sid);
				$alias = $slider->get_alias();
				if($alias !== ''){
					$usage = (in_array($sid, $modals, true)) ? ' usage="modal"' : '';
					
					if(version_compare($wp_version, '5.0', '>=')){ //add gutenberg code
						$ov_data = $slider->get_overview_data();
						$title	 = $slider->get_val($ov_data, 'title', '');
						$_title  = ($title !== '') ? ' data-slidertitle="'.$title.'"' : '';
						$img	 = $slider->get_val($ov_data, array('bg', 'src'), '');
						$_img	 = ($img !== '') ? ',"sliderImage":"'.$img.'"' : '';
						
						$content .= '<!-- wp:themepunch/revslider {"checked":true'.$_img.'} -->'."\n";
						$content .= '<div class="wp-block-themepunch-revslider revslider" data-modal="false"'.$_title.'>';
					}
					
					$content .= '[rev_slider alias="'.$alias.'"'.$usage.'][/rev_slider]'; //this way we will reorder as last comes first
					
					if(version_compare($wp_version, '5.0', '>=')){ //add gutenberg code
						$content .= '</div>'."\n".'<!-- /wp:themepunch/revslider -->'."\n";
					}
				}
			}
		}
		
		if($content !== ''){
			$new_page_id = wp_insert_post(
				array(
					'post_title'    => wp_strip_all_tags('RevSlider Page '.$page_id), //$title
					'post_content'  => $content,
					'post_type'   	=> 'page',
					'post_status'   => 'draft',
					'page_template' => '../public/views/revslider-page-template.php'
				)
			);
			
			if(is_wp_error($new_page_id)) $new_page_id = 0; //fallback to 0
			
			$page_id++;
			update_option('rs_import_page_id', $page_id);
		}
		
		return apply_filters('revslider_create_slider_page', $new_page_id, $added);
	}
	
	/**
	 * add notices from ThemePunch
	 * @since: 4.6.8
	 */
	public function add_notices(){
		$_n = array();
		//check permissions here
		/*if(!current_user_can('administrator')){
			return $_n;
		}*/
		
		$notices = get_option('revslider-notices', false);
		
		if(!empty($notices) && is_array($notices)){
			$n_discarted = get_option('revslider-notices-dc', array());
			
			foreach($notices as $notice){
				//check if global or just on plugin related pages
				if($notice->version === true || !in_array($notice->code, $n_discarted) && version_compare($notice->version, RS_REVISION, '>=')){
					$_n[] = $notice;
				}
			}
		}
		
		//push whatever notices we might need
		return $_n;
	}
	
	/**
	 * get basic v5 Slider data
	 **/
	public function get_v5_slider_data(){
		global $wpdb;
		
		$sliders	= array();
		$do_order	= 'id';
		$direction	= 'ASC';
		
		$slider_data = $wpdb->get_results($wpdb->prepare("SELECT `id`, `title`, `alias`, `type` FROM ".$wpdb->prefix . RevSliderFront::TABLE_SLIDER."_bkp ORDER BY %s %s", array($do_order, $direction)), ARRAY_A);
		
		if(!empty($slider_data)){
			foreach($slider_data as $data){
				if($this->get_val($data, 'type') == 'template') continue;
				
				$sliders[] = $data;
			}
		}
		
		return $sliders;
	}
	
	/**
	 * get basic v5 Slider data
	 **/
	public function reimport_v5_slider($id){
		global $wpdb;
		
		$done = false;
		
		$slider_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix . RevSliderFront::TABLE_SLIDER."_bkp WHERE `id` = %s", $id), ARRAY_A);
		
		if(!empty($slider_data)){
			$slides_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix . RevSliderFront::TABLE_SLIDES."_bkp WHERE `slider_id` = %s", $id), ARRAY_A);
			$static_slide_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix . RevSliderFront::TABLE_STATIC_SLIDES."_bkp WHERE `slider_id` = %s", $id), ARRAY_A);
			
			if(!empty($slides_data)){
				//check if the ID's exist in the new tables, if yes overwrite, if not create
				$slider_v6 = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix . RevSliderFront::TABLE_SLIDER." WHERE `id` = %s", $id), ARRAY_A);
				unset($slider_data['id']);
				if(!empty($slider_v6)){
					/**
					 * push the old data to the already imported Slider
					 **/
					$result = $wpdb->update($wpdb->prefix . RevSliderFront::TABLE_SLIDER, $slider_data, array('id' => $id));
				}else{
					$result	= $wpdb->insert($wpdb->prefix . RevSliderFront::TABLE_SLIDER, $slider_data);
					$id		= ($result) ? $wpdb->insert_id : false;
				}
				if($id !== false){
					foreach($slides_data as $k => $slide_data){
						$slide_data['slider_id'] = $id;
						$slide_v6 = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix . RevSliderFront::TABLE_SLIDES." WHERE `id` = %s", $slide_data['id']), ARRAY_A);
						$slide_id = $slide_data['id'];
						unset($slide_data['id']);
						if(!empty($slide_v6)){
							$result = $wpdb->update($wpdb->prefix . RevSliderFront::TABLE_SLIDES, $slide_data, array('id' => $slide_id));
						}else{
							$result	= $wpdb->insert($wpdb->prefix . RevSliderFront::TABLE_SLIDES, $slide_data);
						}
					}
					if(!empty($static_slide_data)){
						$static_slide_data['slider_id'] = $id;
						$slide_v6 = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix . RevSliderFront::TABLE_STATIC_SLIDES." WHERE `id` = %s", $static_slide_data['id']), ARRAY_A);
						$slide_id = $static_slide_data['id'];
						unset($static_slide_data['id']);
						if(!empty($slide_v6)){
							$result = $wpdb->update($wpdb->prefix . RevSliderFront::TABLE_STATIC_SLIDES, $static_slide_data, array('id' => $slide_id));
						}else{
							$result	= $wpdb->insert($wpdb->prefix . RevSliderFront::TABLE_STATIC_SLIDES, $static_slide_data);
						}
					}
					
					$slider = new RevSliderSlider();
					$slider->init_by_id($id);
					
					$upd = new RevSliderPluginUpdate();
					
					$upd->upgrade_slider_to_latest($slider);
					$done = true;
				}
			}
		}
		
		return $done;
	}
	
	
	/**
	 * returns an object of current system values
	 **/
	public function get_system_requirements(){
		$dir	= wp_upload_dir();
		$basedir = $this->get_val($dir, 'basedir').'/';
		$ml		= ini_get('memory_limit');
		$mlb	= wp_convert_hr_to_bytes($ml);
		$umf	= ini_get('upload_max_filesize');
		$umfb	= wp_convert_hr_to_bytes($umf);
		$pms	= ini_get('post_max_size');
		$pmsb	= wp_convert_hr_to_bytes($pms);
		
		
		$mlg  = ($mlb >= 268435456) ? true : false;
		$umfg = ($umfb >= 33554432) ? true : false;
		$pmsg = ($pmsb >= 33554432) ? true : false;
		
		return array(
			'memory_limit' => array(
				'has' => size_format($mlb),
				'min' => size_format(268435456),
				'good'=> $mlg
			),
			'upload_max_filesize' => array(
				'has' => size_format($umfb),
				'min' => size_format(33554432),
				'good'=> $umfg
			),
			'post_max_size' => array(
				'has' => size_format($pmsb),
				'min' => size_format(33554432),
				'good'=> $pmsg
			),
			'upload_folder_writable'	=> wp_is_writable($basedir),
			'object_library_writable'	=> wp_image_editor_supports(array('methods' => array('resize', 'save'))),
			'server_connect'			=> get_option('revslider-connection', false),
		);
	}
	
}
?>