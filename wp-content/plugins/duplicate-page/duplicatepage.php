<?php
/*
Plugin Name: Duplicate Page
Plugin URI: https://wordpress.org/plugins/duplicate-page/
Description: Duplicate Posts, Pages and Custom Posts using single click.
Author: mndpsingh287
Version: 4.1
Author URI: https://profiles.wordpress.org/mndpsingh287/
License: GPLv2
Text Domain: duplicate-page
*/
if (!defined('DUPLICATE_PAGE_PLUGIN_DIRNAME')) {
    define('DUPLICATE_PAGE_PLUGIN_DIRNAME', plugin_basename(dirname(__FILE__)));
}
if (!class_exists('duplicate_page')):
    class duplicate_page
    {
        /*
        * AutoLoad Hooks
        */        
        public function __construct() 
        {
            $opt = get_option('duplicate_page_options');
            register_activation_hook(__FILE__, array(&$this, 'duplicate_page_install'));
            add_action('admin_menu', array(&$this, 'duplicate_page_options_page'));
            add_filter('plugin_action_links', array(&$this, 'duplicate_page_plugin_action_links'), 10, 2);
            add_action('admin_action_dt_duplicate_post_as_draft', array(&$this, 'dt_duplicate_post_as_draft'));
            add_filter('post_row_actions', array(&$this, 'dt_duplicate_post_link'), 10, 2);
            add_filter('page_row_actions', array(&$this, 'dt_duplicate_post_link'), 10, 2);
            if (isset($opt['duplicate_post_editor']) && $opt['duplicate_post_editor'] == 'gutenberg') {
                add_action('admin_head', array(&$this, 'duplicate_page_custom_button_guten'));
            } else {
                add_action('post_submitbox_misc_actions', array(&$this, 'duplicate_page_custom_button_classic'));
            }
            add_action('wp_before_admin_bar_render', array(&$this, 'duplicate_page_admin_bar_link'));
            add_action('init', array(&$this, 'duplicate_page_load_text_domain'));
            add_action('wp_ajax_mk_dp_close_dp_help', array($this, 'mk_dp_close_dp_help'));
        }

        /*
        * Localization - 19-dec-2016
        */
        public function duplicate_page_load_text_domain()
        {
            load_plugin_textdomain('duplicate-page', false, DUPLICATE_PAGE_PLUGIN_DIRNAME.'/languages');
        }

        /*
        * Activation Hook
        */
        public function duplicate_page_install()
        {
            $defaultsettings = array(
                                             'duplicate_post_status' => 'draft',
                                             'duplicate_post_redirect' => 'to_list',
                                             'duplicate_post_suffix' => '',
                                             'duplicate_post_editor' => 'classic',
                                             );
            $opt = get_option('duplicate_page_options');
            if (!$opt['duplicate_post_status']) {
                update_option('duplicate_page_options', $defaultsettings);
            }
        }

        /*
        Action Links
        */
        public function duplicate_page_plugin_action_links($links, $file)
        {
            if ($file == plugin_basename(__FILE__)) {
                $duplicate_page_links = '<a href="'.get_admin_url().'options-general.php?page=duplicate_page_settings">'.__('Settings', 'duplicate-page').'</a>';
                $duplicate_page_donate = '<a href="https://www.webdesi9.com/donate/?plugin=duplicate-page" title="Donate Now" target="_blank" style="font-weight:bold">'.__('Donate', 'duplicate-page').'</a>';
                array_unshift($links, $duplicate_page_donate);
                array_unshift($links, $duplicate_page_links);
            }

            return $links;
        }

        /*
        * Admin Menu
        */
        public function duplicate_page_options_page()
        {
            add_options_page(__('Duplicate Page', 'duplicate-page'), __('Duplicate Page', 'duplicate-page'), 'manage_options', 'duplicate_page_settings', array(&$this, 'duplicate_page_settings'));
        }

        /*
        * Duplicate Page Admin Settings
        */
        public function duplicate_page_settings()
        {
            if (current_user_can('manage_options')) {
                include 'inc/admin-settings.php';
            }
        }

        /*
        * Main function
        */
        public function dt_duplicate_post_as_draft()
        {
           /*
           * get Nonce value
           */
           $nonce = $_REQUEST['nonce'];
            /*
            * get the original post id
            */
           $post_id = (isset($_GET['post']) ? intval($_GET['post']) : intval($_POST['post']));

           if(wp_verify_nonce( $nonce, 'dt-duplicate-page-'.$post_id) && current_user_can('edit_posts')) {
           // verify Nonce  
            global $wpdb;
            $opt = get_option('duplicate_page_options');
            $suffix = isset($opt['duplicate_post_suffix']) && !empty($opt['duplicate_post_suffix']) ? ' -- '.$opt['duplicate_post_suffix'] : '';
            $post_status = !empty($opt['duplicate_post_status']) ? $opt['duplicate_post_status'] : 'draft';
            $redirectit = !empty($opt['duplicate_post_redirect']) ? $opt['duplicate_post_redirect'] : 'to_list';
            if (!(isset($_GET['post']) || isset($_POST['post']) || (isset($_REQUEST['action']) && 'dt_duplicate_post_as_draft' == $_REQUEST['action']))) {
                wp_die('No post to duplicate has been supplied!');
            }
            $returnpage = '';            
            /*
            * and all the original post data then
            */
            $post = get_post($post_id);
            /*
            * if you don't want current user to be the new post author,
            * then change next couple of lines to this: $new_post_author = $post->post_author;
            */
            $current_user = wp_get_current_user();
            $new_post_author = $current_user->ID;
            /*
            * if post data exists, create the post duplicate
            */
            if (isset($post) && $post != null) {
                /*
                * new post data array
                */
                $args = array(
                     'comment_status' => $post->comment_status,
                     'ping_status' => $post->ping_status,
                     'post_author' => $new_post_author,
                     'post_content' => (isset($opt['duplicate_post_editor']) && $opt['duplicate_post_editor'] == 'gutenberg') ? wp_slash($post->post_content) : $post->post_content,
                     'post_excerpt' => $post->post_excerpt,
                     //'post_name' => $post->post_name,
                     'post_parent' => $post->post_parent,
                     'post_password' => $post->post_password,
                     'post_status' => $post_status,
                     'post_title' => $post->post_title.$suffix,
                     'post_type' => $post->post_type,
                     'to_ping' => $post->to_ping,
                     'menu_order' => $post->menu_order,
                 );
                /*
                * insert the post by wp_insert_post() function
                */
                $new_post_id = wp_insert_post($args);
                /*
                * get all current post terms ad set them to the new post draft
                */
                $taxonomies = get_object_taxonomies($post->post_type);
                if (!empty($taxonomies) && is_array($taxonomies)):
                 foreach ($taxonomies as $taxonomy) {
                     $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
                     wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
                 }
                endif;
                /*
                * duplicate all post meta
                */
                    $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
				 if (count($post_meta_infos)!=0) {
				     $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
				     foreach ($post_meta_infos as $meta_info) {
                        $meta_key = sanitize_text_field($meta_info->meta_key);
                        $meta_value = addslashes($meta_info->meta_value);
                        $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
                        }
                        $sql_query.= implode(" UNION ALL ", $sql_query_sel);
                        $wpdb->query($sql_query);
					} 
                /*
                * finally, redirecting to your choice
                */
                if ($post->post_type != 'post'):
                       $returnpage = '?post_type='.$post->post_type;
                endif;
                if (!empty($redirectit) && $redirectit == 'to_list'):
                        wp_redirect(admin_url('edit.php'.$returnpage)); elseif (!empty($redirectit) && $redirectit == 'to_page'):
                        wp_redirect(admin_url('post.php?action=edit&post='.$new_post_id)); else:
                        wp_redirect(admin_url('edit.php'.$returnpage));
                endif;
                exit;
            } else {
                wp_die('Error! Post creation failed, could not find original post: '.$post_id);
            }
          } else {
            wp_die('Security check issue, Please try again.');
          }
        }

        /*
         * Add the duplicate link to action list for post_row_actions
         */
        public function dt_duplicate_post_link($actions, $post)
        {
            $opt = get_option('duplicate_page_options');
            $post_status = !empty($opt['duplicate_post_status']) ? $opt['duplicate_post_status'] : 'draft';
            if (current_user_can('edit_posts')) {
                $actions['duplicate'] = '<a href="admin.php?action=dt_duplicate_post_as_draft&amp;post='.$post->ID.'&amp;nonce='.wp_create_nonce( 'dt-duplicate-page-'.$post->ID ).'" title="Duplicate this as '.$post_status.'" rel="permalink">'.__('Duplicate This', 'duplicate-page').'</a>';
            }

            return $actions;
        }

        /*
          * Add the duplicate link to edit screen - classic editor
          */
        public function duplicate_page_custom_button_classic()
        {
            global $post;
            $opt = get_option('duplicate_page_options');
            $post_status = !empty($opt['duplicate_post_status']) ? $opt['duplicate_post_status'] : 'draft';
            $html = '<div id="major-publishing-actions">';
            $html .= '<div id="export-action">';
            $html .= '<a href="admin.php?action=dt_duplicate_post_as_draft&amp;post='.$post->ID.'&amp;nonce='.wp_create_nonce( 'dt-duplicate-page-'.$post->ID ).'" title="Duplicate this as '.$post_status.'" rel="permalink">'.__('Duplicate This', 'duplicate-page').'</a>';
            $html .= '</div>';
            $html .= '</div>';
            echo $html;
        }

        /*
         * Add the duplicate link to edit screen - gutenberg
         */
        public function duplicate_page_custom_button_guten()
        {
            global $post;
            if ($post) {
                $opt = get_option('duplicate_page_options');
                $post_status = !empty($opt['duplicate_post_status']) ? $opt['duplicate_post_status'] : 'draft';
                if (isset($opt['duplicate_post_editor']) && $opt['duplicate_post_editor'] == 'gutenberg') {
                    ?>
             <style>
             .duplicate_page_link_guten {
                text-align: center;
                margin-top: 15px;
             }
             .duplicate_page_link_guten a {
                text-decoration: none;
                display: block;
                height: 30px;
                line-height: 28px;
                padding: 0 12px 2px;
                border-color: #ccc;
                background: #f7f7f7;
                box-shadow: inset 0 -1px 0 #ccc;
                border-radius: 3px;
                border-width: 1px;
                border-style: solid;
            }
            .duplicate_page_link_guten a:hover {
                background: #fafafa;
                border-color: #999;
                box-shadow: inset 0 -1px 0 #999;
            }
             </style>       
             <script>
              jQuery(window).load(function(e){
                var dp_post_id = "<?php echo $post->ID; ?>";
                var dtnonce = "<?php echo wp_create_nonce( 'dt-duplicate-page-'.$post->ID );?>"; 
                var dp_post_title = "Duplicate this as <?php echo $post_status; ?>";
                var dp_duplicate_link = '<div class="duplicate_page_link_guten">';
                    dp_duplicate_link += '<a href="admin.php?action=dt_duplicate_post_as_draft&amp;post='+dp_post_id+'&amp;nonce='+dtnonce+'" title="'+dp_post_title+'">Duplicate This</a>';
                    dp_duplicate_link += '</div>';
                jQuery('.edit-post-post-status').append(dp_duplicate_link);
                });
              </script>
            <?php
                }
            }
        }

        /*
        * Admin Bar Duplicate This Link
        */
        public function duplicate_page_admin_bar_link()
        {
            global $wp_admin_bar, $post;
            $opt = get_option('duplicate_page_options');
            $post_status = !empty($opt['duplicate_post_status']) ? $opt['duplicate_post_status'] : 'draft';
            $current_object = get_queried_object();
            if (empty($current_object)) {
                return;
            }
            if (!empty($current_object->post_type)
            && ($post_type_object = get_post_type_object($current_object->post_type))
            && ($post_type_object->show_ui || $current_object->post_type == 'attachment')) {
                $wp_admin_bar->add_menu(array(
                'parent' => 'edit',
                'id' => 'duplicate_this',
                'title' => __('Duplicate This as '.$post_status.'', 'duplicate-page'),
                'href' => admin_url().'admin.php?action=dt_duplicate_post_as_draft&amp;post='.$post->ID.'&amp;nonce='.wp_create_nonce( 'dt-duplicate-page-'.$post->ID )
                ));
            }
        }

        /*
         * Redirect function
        */
        public static function dp_redirect($url)
        {
            echo '<script>window.location.href="'.$url.'"</script>';
        }
        /*
         Load Help Desk
        */
        public function load_help_desk()
        {
            $mkcontent = '';
            $mkcontent .= '<div class="dpmrs">';
            $mkcontent .= '<div class="l_dpmrs">';
            $mkcontent .= '';
            $mkcontent .= '</div>';
            $mkcontent .= '<div class="r_dpmrs">';
            $mkcontent .= '<a class="close_dp_help fm_close_btn" href="javascript:void(0)" data-ct="rate_later" title="close">X</a><strong>Duplicate Page</strong><p>We love and care about you. Our team is putting maximum efforts to provide you the best functionalities. It would be highly appreciable if you could spend a couple of seconds to give a Nice Review to the plugin to appreciate our efforts. So we can work hard to provide new features regularly :)</p><a class="close_dp_help fm_close_btn_1" href="javascript:void(0)" data-ct="rate_later" title="Remind me later">Later</a> <a class="close_dp_help fm_close_btn_2" href="https://wordpress.org/support/plugin/duplicate-page/reviews/?filter=5" data-ct="rate_now" title="Rate us now" target="_blank">Rate Us</a> <a class="close_dp_help fm_close_btn_3" href="javascript:void(0)" data-ct="rate_never" title="Not interested">Never</a>';
            $mkcontent .= '</div></div>';
            if (false === ($mk_dp_close_dp_help_c = get_option('mk_fm_close_fm_help_c'))) {
                echo apply_filters('the_content', $mkcontent);
            }
        }
        /*
         Close Help
        */
        public function mk_dp_close_dp_help()
        {
            $what_to_do = sanitize_text_field($_POST['what_to_do']);
            $expire_time = 15;
            if ($what_to_do == 'rate_now' || $what_to_do == 'rate_never') {
                $expire_time = 365;
            } elseif ($what_to_do == 'rate_later') {
                $expire_time = 15;
            }
            if (false === ($mk_fm_close_fm_help_c = get_option('mk_fm_close_fm_help_c'))) {
                $set = update_option('mk_fm_close_fm_help_c', 'done');
                if ($set) {
                    echo 'ok';
                } else {
                    echo 'oh';
                }
            } else {
                echo 'ac';
            }
            die;
        }
        /*
        Custom Assets
        */
        public function custom_assets() {
            wp_enqueue_style( 'duplicate-page', plugins_url( '/css/duplicate_page.css', __FILE__ ) );
            wp_enqueue_script( 'duplicate-page', plugins_url( '/js/duplicate_page.js', __FILE__ ) );
        }
    }
new duplicate_page();
endif;
?>