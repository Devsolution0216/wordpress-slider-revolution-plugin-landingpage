<?php
function shiftkey_redux_options($choices= array()){
    $options = array();

    foreach ($choices as $ckey=>$cval) {
        $cval = array_filter($cval);
        if (isset($cval['src'])) {
            $options[$cval['value']] = array( 'alt' => $cval['label'], 'img' => $cval['src'] );
        } else {
            $options[$cval['value']] = $cval['label']; 
        }
    }
    return $options;
}

//include all available options
include SHIFTKEY_DIR . '/admin/options/general_options.php';
include SHIFTKEY_DIR . '/admin/options/background_options.php';
include SHIFTKEY_DIR . '/admin/options/header_options.php';
include SHIFTKEY_DIR . '/admin/options/sidebar_options.php';
include SHIFTKEY_DIR . '/admin/options/footer_options.php';
include SHIFTKEY_DIR . '/admin/options/blog_options.php';
include SHIFTKEY_DIR . '/admin/options/portfolio_options.php';
include SHIFTKEY_DIR . '/admin/options/typography_options.php';
include SHIFTKEY_DIR . '/admin/options/styling_options.php';
include SHIFTKEY_DIR . '/admin/options/slider_options.php';
include SHIFTKEY_DIR . '/admin/options/404_options.php';
include SHIFTKEY_DIR . '/admin/options/woo_options.php';
function shiftkey_woo_ot_section() {
    return array(
         'id' => 'woo_options',
        'title' => esc_attr__( 'Woo options', 'shiftkey' ) 
    );
}

function shiftkey_theme_options( ) {
    /* OptionTree is not loaded yet */
    if ( !function_exists( 'ot_settings_id' ) )
        return false;
    /**
    
    * Get a copy of the saved settings array. 
    
    */    
    $settings = array(
             array(
                'id' => 'general_options',
                'title' => esc_attr__( 'General options', 'shiftkey' ),
                'fields' => shiftkey_general_options(),
            ),
            array(
                 'id' => 'header_options',
                'title' => esc_attr__( 'Header options', 'shiftkey' ),
                'fields' => shiftkey_header_options(), 
            ),
            array(
                 'id' => 'background_options',
                'title' => esc_attr__( 'Background Options', 'shiftkey' ),
                'fields' => shiftkey_background_options(), 
            ),
            array(
                 'id' => 'footer_options',
                'title' => esc_attr__( 'Footer options', 'shiftkey' ),
                'fields' => shiftkey_footer_options(), 
            ),
            array(
                 'id' => 'sidebar_option',
                'title' => esc_attr__( 'Sidebar options', 'shiftkey' ),
                'fields' => shiftkey_sidebar_options(), 
            ),
            array(
                 'id' => 'blog_options',
                'title' => esc_attr__( 'Blog options', 'shiftkey' ),
                'fields' => shiftkey_blog_options(), 
            ),
            array(
                 'id' => '404_options',
                'title' => esc_attr__( '404 page', 'shiftkey' ),
                'fields' => shiftkey_404_options(), 
            ),
            array(
                 'id' => 'fonts',
                'title' => esc_attr__( 'Typography options', 'shiftkey' ),
                'fields' => shiftkey_typography_options(), 
            ),
            array(
                 'id' => 'styling_options',
                'title' => esc_attr__( 'Styling options', 'shiftkey' ),
                'fields' => shiftkey_styling_options(), 
            ),
        );
   
    return $settings;
}

if( !class_exists( 'Shiftkey_OptionTree2Redux_Data' ) ) {
    class Shiftkey_OptionTree2Redux_Data {       

        public function __construct() { 
           add_filter('shiftkey_theme_options', array( $this, '_theme_options' ), 10, 2 );
        }

       

        public function _theme_options( $args, $section ){
            $withWarnings = false;
            if ( empty( $args ) ) {
                return $args;
            }
            $options = array();
            foreach($args as $key=>$value) {
                if(isset($value['id'])){
                    $options[] = $this->cleanSetting($value);
                }            
                
            }

            return $options;
        }

        public function clean_condition( $condition, $operator = 'equals' ){
            $required = array();

            if( $condition == '' ) return $required;

            $array = explode(',', $condition);
            $array = array_filter($array);

            if( !empty($array) ){
              foreach ($array as $key => $value) {
                $field_id = $field_value = '';

                $_arr = explode(':', $value);                
                if(isset($_arr[0]) && ($_arr[0] != '')){
                  $field_id = trim($_arr[0]);
                }

                if(isset($_arr[1]) && ($_arr[1] != '')):
                  $field_value = trim($_arr[1]);
                  $__arr = explode('(', $field_value);
                  if( isset($__arr[0]) && ($__arr[0] != '') ){                      
                      $operator = ($__arr[0] == 'not' )? '!=' : $operator;
                  }

                  if(isset($__arr[1]) && ($__arr[1] != '')):                    
                      $field_value = trim(str_replace(')', '', $__arr[1]));                       
                  endif;
                endif; 

                $required[] = array(
                  $field_id,
                  $operator,
                  $field_value
                );
              }
              if( count($required) == 1 ){
                $required = $required[0];
              }
              $json = json_encode($required);
              $required = json_decode($json);             
            }
            //$required = array_filter($required);

            return $required;
        }

        public function filter_by_value ($array, $index, $value){
           $newarray = array();
            if(is_array($array) && count($array)>0) {
                foreach(array_keys($array) as $key){
                    $temp[$key] = $array[$key][$index];
                    
                    if ($temp[$key] == $value){
                        $newarray[] = $array[$key];                        
                    }
                }
              }

           if( count($newarray) == 1 ) $newarray = $newarray[0];  
          return $newarray;
        } 

        public function cleanSetting($value, $withWarnings = false) {
            $value = array_filter($value);
            

            if (isset($value['label'])) {
                $value['title'] = $value['label'];
                unset($value['label']); 
            }
            if (isset($value['std'])) {
                $value['default'] = $value['std'];
                unset($value['std']);   
            }

            if (isset($value['min_max_step'])) {
                $min_max_step = $value['min_max_step'];
                if( $min_max_step != '' ){
                    $arr = explode(',', $min_max_step);
                    $value['min'] = isset($arr[0])? intval($arr[0]) : '';
                    $value['max'] = isset($arr[1])? intval($arr[1]) : '';
                    $value['step'] = isset($arr[2])? intval($arr[2]) : '';
                }
                unset($value['min_max_step']);   
            }

            if (isset($value['condition'])) {
                $required = $this->clean_condition($value['condition']);
                $value['required'] = $required; 
                unset($value['condition']);                
            }              

            if (isset($value['choices'])) {
                $value['options'] = array();

                foreach ($value['choices'] as $ckey=>$cval) {
                    $cval = array_filter($cval);
                    if (isset($cval['src'])) {
                        $value['options'][$cval['value']] = array( 'alt' => $cval['label'], 'img' => $cval['src'] );
                    } else {
                        $value['options'][$cval['value']] = $cval['label']; 
                    }
                }
                unset($value['choices']);   
            }

            switch ($value['type']) {
                case "background":

                break;
                case "iconpicker_input":
                  $value['type'] = "icon_select";
                  $value['prefix'] = "fa";
                  $value['selector'] = "fa-";
                break;
                case "icon_select":
                  $value['type'] = "icon_select";
                  $value['prefix'] = "fa";
                  $value['selector'] = "fa-";
                break;
                case "on-off":
                    $value['type'] = "button_set";
                    $value['options'] = array(
                            'on' => 'ON',
                            'off' => 'OFF',
                        );
                break;
                case "tab":
                    $value['type'] = "info";
                break;
                case "numeric-slider":
                    $value['type'] = "slider";
                break;
                case "category-checkbox":
                    $value['type'] = "checkbox";
                    $value['data'] = "category";
                    $value['args'] = array( 'hide_empty' => false );                
                break;
                case "category-select":
                    $value['type'] = "select";
                    $value['data'] = "category";
                    $value['args'] = array( 'hide_empty' => false );
                    $value['multi'] = false;
                break;
                case "checkbox":
                    $value['type'] = "checkbox";
                    if (isset($value['options'])) {

                    }
                break;
                case "colorpicker":
                    $value['type'] = "color";
                    $value['transparent'] = false;
                break;
                case "css":
                    $value['type'] = "ace_editor";
                    if (isset($value['rows'])) {
                        unset($value['rows']);
                    }
                    $value['mode'] = 'css';
                    $value['theme'] = 'monokai';
                break;
                case "custom-post-type-select":
                    $value['multi'] = false;
                    $value['type'] = "select";
                    /* setup the post types */
                    $value['post_type'] = isset( $value['post_type'] ) ? explode( ',', $value['post_type'] ) : array( 'post' );
                    /* query posts array */
                    $value['args'] = apply_filters( 'ot_type_custom_post_type_checkbox_query', array( 'post_type' => $value['post_type'], 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'post_status' => 'any' ), $value['id'] );
                    unset($value['post_type']); 
                    $value['data'] = "posts";               
                break;
                case "custom-post-type-checkbox":
                    $value['type'] = "checkbox";
                    /* setup the post types */
                    $value['post_type'] = isset( $value['post_type'] ) ? explode( ',', $value['post_type'] ) : array( 'post' );
                    /* query posts array */
                    $value['args'] = apply_filters( 'ot_type_custom_post_type_checkbox_query', array( 'post_type' => $value['post_type'], 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'post_status' => 'any' ), $value['id'] );
                    unset($value['post_type']); 
                    $value['data'] = "posts";               
                break;
                case "list-item":
                    $value['type'] = "repeater"; 
                    $value['sortable'] = true;
                    if (isset($value['settings'])) {
                        $value['fields'] = array();
                        foreach($value['settings'] as $setting) {
                            $_fields = $this->cleanSetting($setting);
                            $value['fields'][] = $_fields;                             
                        }
                    }

                    if (isset($value['default']) && !empty($value['default'])) { 

                        $temp_defaults = array_filter($value['default']); 
                        $temp_fields = array_filter($value['fields']); 
                        $value['default'] = array(); 
                        foreach($temp_defaults as $_key => $default) {
                          $_default = array();
                            foreach ($default as $id => $std) {

                                
                                $_typeArr = $this->filter_by_value($temp_fields, 'id', $id);
                                $_default[$id] = $this->convertValue($std, $_typeArr['type']);
                            }
                            $value['default'][] = $_default;
                        }
                         
                    }
                    
                    
                break;
                case "slider":
                    $value['type'] = "slides";
                break;                  
                case "measurement":
                    $value['type'] = "spacing";
                    $value['all'] = true;
                break;  
                case "numeric_slider":
                    $value['type'] = "slider";
                    if (isset($value['min_max_step'])) {
                        $min_max_step = explode(',', $value['min_max_step']);
                        $value['min'] = $min_max_step[0];
                        $value['max'] = $min_max_step[1];
                        $value['step'] = $min_max_step[2];
                    } else {
                        $value['min'] = 1;
                        $value['max'] = 100;
                        $value['step'] = 1;
                    }                 
                break;  
                case "page-select":
                    $value['type'] = "select";
                    $value['data'] = "page";
                    $value['multi'] = false;
                break;                      
                case "page-checkbox":
                    $value['type'] = "checkbox";
                    $value['data'] = "page";
                break;                      
                case "post-select":
                    $value['type'] = "select";
                    $value['data'] = "post";
                    $value['multi'] = false;
                break;                      
                case "post-checkbox":
                    $value['type'] = "checkbox";
                    $value['data'] = "post";
                break;                      
                case "radio":
                break;                      
                case "radio-image":
                    $value['type'] = "image_select";
                    if (!isset($value['options'])) {
                        $value['options'] = array(
                            
                            'left-sidebar' => array('alt' => 'Left Sidebar', 'img' => ReduxFramework::$_url.'assets/img/2cl.png'),
                            'right-sidebar' => array('alt' => 'Right Sidebar', 'img' => ReduxFramework::$_url.'assets/img/2cr.png'),
                            'full-width' => array('alt' => 'full-width', 'img' => ReduxFramework::$_url.'assets/img/1col.png'),
                            'dual-sidebar' => array('alt' => 'Dual Sidebar', 'img' => ReduxFramework::$_url.'assets/img/3cm.png'),
                            'left-dual-sidebar' => array('alt' => 'Left Dual Sidebar', 'img' => ReduxFramework::$_url.'assets/img/3cl.png'),
                            'right-dual-sidebar' => array('alt' => 'Right Dual Sidebar', 'img' => ReduxFramework::$_url.'assets/img/3cr.png')
                        );
                    }
                break;                      
                case "select":
                
                break;                      
                case "sidebar-select":
                    $value['type'] = "select";
                    $value['data'] = "sidebar";
                    $value['multi'] = false;
                break;  
                case "sidebar-checkbox":
                    $value['type'] = "checkbox";
                    $value['data'] = "sidebar";

                break;                  
                case "tag-checkbox":
                    $value['type'] = "checkbox";
                    $value['title'] = $value['title'];
                    $value['data'] = "tags";
                break;                      
                case "tag-select":
                    $value['type'] = "select";
                    $value['title'] = $value['title'];
                    $value['data'] = "tags";
                    $value['multi'] = false;
                break;                      
                case "taxonomy-select":
                    $taxonomy = isset( $value['taxonomy'] ) ? explode( ',', $value['taxonomy'] ) : array( 'category' );
                    unset( $value['taxonomy'] );
                    $value['args'] = array( 'hide_empty' => false, 'taxonomy' => $taxonomy );
                    $value['type'] = "select";
                    $value['data'] = "category";
                    $value['multi'] = false;
                break;                      
                case "taxonomy-checkbox":
                    $taxonomy = isset( $value['taxonomy'] ) ? explode( ',', $value['taxonomy'] ) : array( 'category' );
                    unset( $value['taxonomy'] );
                    $value['args'] = array( 'hide_empty' => false, 'taxonomy' => $taxonomy );               
                    $value['type'] = "checkbox";
                    $value['data'] = "category";
                break;                      
                case "text":
                case "input":
                
                break;                      
                case "textarea":
                    $value['type'] = "editor";
                break;                      
                case "textarea-simple":
                    $value['type'] = "textarea";
                break;                      
                case "textblock":
                    $value['type'] = "raw";
                    $value['content'] = $value['desc'];
                    unset($value['desc'], $value['title']);
                break;                      
                case "textblock-titled":
                    $value['type'] = "info";
                break;                      
                case "typography":
                
                break;                      
                case "upload":
                    $value['type'] = "media";
                    $value['url'] = true;                    
                break;               
                case "gallery":
                break;              
                default:
                $value['type'] = "info";
                    
                
                    if ($withWarnings) {
                        $content = "<h3 style='color: red;'>Found a field with an unknown type!</h3> <p>Perhaps this was a custom field and will need to be remade for use within Redux. This was the field's configuration:</p>";
                        $content .= "<pre style='overflow:auto;border: 2px dashed #eee;padding: 2px 5px; width: 100%;'>";
                        ob_start();
                        var_dump($value);
                        $content .= ob_get_clean();
                        $content .= "</pre>";
                        $value['desc'] = $content;
                        $value['type'] = "info";
                        $value['raw_html'] = true;                          
                    }                    
                
                    break;                  
            }

            if (isset($value['default']) && !empty($value['default'])) {
                $value['default'] = $this->convertValue($value['default'], $value['type']);
            }

            if($value['type'] == 'repeater'){
              //print_r($value);
            }
            

                      
            return $value;
        }       

        function get_attachment_id_by_url( $url ) {
            // Split the $url into two parts with the wp-content directory as the separator.
            $parse_url  = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );
            
            // Get the host of the current site and the host of the $url, ignoring www.
            $this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
            $file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );
         
            // Return nothing if there aren't any $url parts or if the current host and $url host do not match.
            if ( ! isset( $parse_url[1] ) || empty( $parse_url[1] ) || ( $this_host != $file_host ) )
                return;
         
            // Now we're going to quickly search the DB for any attachment GUID with a partial path match.
            // Example: /uploads/2013/05/test-image.jpg
            global $wpdb;
         
            $prefix     = $wpdb->prefix;
            $attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM " . $prefix . "posts WHERE guid RLIKE %s;", $parse_url[1] ) );
         
            // Returns null if no attachment is found.
            return $attachment[0];
        }       

        function convertValue($value, $type) {
            switch ($type) {
                case "text":
                    break;  
                case "media":
                        $value = array('url' => $value );
                    break;                                   
                case "icon_select":
                        $value = isset($value['icon'])? $value['icon'] : '';
                    break; 
                case "iconpicker_input":
                    $value = isset($value['icon'])? $value['icon'] : '';
                break;   
                case "taxonomy-checkbox":
                case "tag-checkbox":
                case "sidebar-checkbox":
                case "post-checkbox":
                case "page-checkbox":
                case "custom-post-type-checkbox":
                case "category-checkbox":       
                    foreach ($value as $key => $val) {                    
                            $value[$key] = 1;                        
                    }

                    break;                          
                default:
                    break;
            }
            return $value;          
        }   
    }

}
new Shiftkey_OptionTree2Redux_Data();

include SHIFTKEY_DIR . '/admin/framework/redux/options-functions.php';