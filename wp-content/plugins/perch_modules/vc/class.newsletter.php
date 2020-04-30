<?php
class PerchNewsletter extends PerchVcMap{

	function __construct() {
		add_action( 'init', array( $this, 'create_newsletter_group' ) );
	}	

	public static function newsletter_form_name_field($data){
		$name_html = '';

		$show_name     = ! empty( $data['name_visible'] ) ? $data['name_visible'] : false;		
		$show_label     = ! empty( $data['name_label_visible'] ) ? $data['name_label_visible'] : false;		
		$required_name = ! empty( $data['name_required'] ) ? $data['name_required'] : false;
		$placeholder = ! empty( $data['name_placeholder'] ) ? ' placeholder="'.$data['name_placeholder'].'"' : '';
		$before = ! empty( $data['name_field_before'] ) ? $data['name_field_before'] : '';
		$after = ! empty( $data['name_field_after'] ) ? $data['name_field_after'] : '';

		if ( $show_name ) {
			$required = ( 'yes' === $required_name ) ? ' *' : '';
			$name_label = ( 'yes' === $show_label ) ? '<label>'.__( 'Name', 'perch' ).$required.'</label>' : '';
			$required   = ( 'yes' === $required_name ) ? 'required' : '';
			$name_html  =  $before. $name_label . '<input type="text" class="form-control" name="name" '.$placeholder.' ' . $required . '/>'.$after;
		}

		$name_html = apply_filters( 'perch_modules/newsletter_form/name_field', $name_html, $data );

		return $name_html;
	}

	public static function newsletter_form_email_field($data){
		$show_label     = ! empty( $data['email_label_visible'] ) ? $data['email_label_visible'] : false;
		$email_label = ( 'yes' === $show_label ) ? '<label>'.__( 'Email *', 'perch' ).'</label>' : '';
		$placeholder = ! empty( $data['email_placeholder'] ) ? ' placeholder="'.$data['email_placeholder'].'"' : '';
		$before = ! empty( $data['email_field_before'] ) ? $data['email_field_before'] : '';
		$after = ! empty( $data['email_field_after'] ) ? $data['email_field_after'] : '';

		$email_html = $before . $email_label . '<input class="es_required_field form-control" type="email" name="email" '.$placeholder.' required/>'.$after;

		$email_html = apply_filters( 'perch_modules/newsletter_form/email_field', $email_html, $data );

		return $email_html;
	}	


	public static function newsletter_form_button_field($data, $unique_id){		
	
		$btn_atts = array(
    		'type' => "submit",
    		'id' => "es_subscription_form_submit_".$unique_id,
    		'name' =>"es_txt_button",
    	);
		$extra_class = ! empty( $data['button_extra_class'] ) ? $data['button_extra_class'] : ' btn-lg';
    	$before = ! empty( $data['button_field_before'] ) ? $data['button_field_before'] : '';
		$after = ! empty( $data['button_field_after'] ) ? $data['button_field_after'] : '';
		$form_button_style = ! empty( $data['form_button_style'] ) ? $data['form_button_style'] : 'text_button';
		$form_button_icon = ! empty( $data['form_button_icon'] ) ? $data['form_button_icon'] : 'fas fa-arrow-right';

    	if( $form_button_style == 'text_button' ){
        $button_html =  $before.PerchVcMapButtons::single_button_html($data, 'es_subscription_form_submit es_submit_button'.$extra_class, 'button', $btn_atts).$after;
    	}else{
    		extract($data);
    		$button_style = str_replace("btn-","",$button_style).'-color';
    		$button_html =  $before.'<span class="input-group-btn">	                	
		                    <button type="submit" id="es_txt_button" class="btn btn-simple es_textbox_button es_submit_button" name="es_txt_button" title="'.esc_attr($button_text).'">
		                    	 <i class="'.$form_button_icon.' '.esc_attr($button_style).'"></i>
		                    </button>
		                </span>'.$after;
    	}

		$button_html = apply_filters( 'perch_modules/newsletter_form/button_field', $button_html, $data );

		return $button_html;
	}
		

	public static function es_get_form_horizontal( $instance ) {

		global $es_includes;

		// Compatibility for GDPR
		$active_plugins = get_option( 'active_plugins', array() );
		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		if ( ! isset( $es_includes ) || $es_includes !== true ) {
			$es_includes = true;
		}
		$es_desc  = $instance['es_desc'];		
		$es_name  = $instance['enable_name'];
		$es_group = $instance['es_group'];
		$es_pre   = $instance['es_pre'];
		$name_placeholder  = $instance['name'];
		$email_placeholder  = $instance['email'];
		$email_wrapper_class = ($es_name == "yes")? 'col-lg-5' : 'col-lg-9';
		$form_class = ($instance['el_class'] != '')? $instance['el_class'] : 'quick-form-horizontal form-holder register-form mb-30';

		$instance['form_class'] = $form_class;	
		$instance['email_placeholder'] = $instance['email'];
		$instance['email_field_before'] = '<div class="'.$email_wrapper_class.'">';	
		$instance['email_field_after'] = '</div>';	
		$instance['name_placeholder'] = $instance['name'];	
		$instance['name_visible'] = $instance['enable_name'];
		$instance['name_field_before'] = '<div class="col-lg-4">';	
		$instance['name_field_after'] = '</div>';	
		$instance['fields_before'] = '<div class="row">';	
		$instance['fields_after'] = '</div>';
		$instance['form_button_style'] = 'text_button';
		$instance['button_field_before'] = '<div class="col-lg-3" style="padding-left: 0">';	
		$instance['button_field_after'] = '</div>';
		ob_start();

		self::render_form($instance);
		
      return $es_form = ob_get_clean();

	}

	public static function es_get_form( $instance ) {

		$instance['form_class'] = 'register-form';	
		$instance['email_placeholder'] = $instance['email'];	
		$instance['name_placeholder'] = $instance['name'];	
		$instance['name_visible'] = $instance['enable_name'];	

		ob_start();
		self::render_form($instance);
		return $es_form = ob_get_clean();

	}

	public static function es_get_form_simple( $instance ) {

		global $es_includes;		

		// Compatibility for GDPR
		$active_plugins = get_option( 'active_plugins', array() );
		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		if ( ! isset( $es_includes ) || $es_includes !== true ) {
			$es_includes = true;
		}
		$es_desc  = $instance['es_desc'];		
		$es_name  = $instance['enable_name'];
		$es_group = $instance['es_group'];
		$es_pre   = $instance['es_pre'];
		$name_placeholder  = $instance['name'];
		$email_placeholder  = $instance['email'];
		$button_text = $instance['button_text'];
		$button_style = $instance['button_style'];
		$button_style = str_replace("btn-","",$button_style).'-color';
		$form_class = ($instance['el_class'] != '')? $instance['el_class'] : 'newsletter-form newsletter-form-simple';

		$instance['form_class'] = $form_class;	
		$instance['email_placeholder'] = $instance['email'];	
		$instance['name_placeholder'] = $instance['name'];	
		$instance['name_visible'] = $instance['enable_name'];
		$instance['fields_before'] = '<div class="input-group">';	
		$instance['fields_after'] = '</div>';	
		$instance['email_field_before'] = '';	
		$instance['email_field_after'] = '';
		$instance['form_button_style'] = ! empty( $instance['form_button_style'] ) ? $instance['form_button_style'] : 'icon';

		ob_start();
		self::render_form($instance);
		return $es_form = ob_get_clean();

	}

	public static function es_get_widget_form( $instance ) {

		global $es_includes;		

		// Compatibility for GDPR
		$active_plugins = get_option( 'active_plugins', array() );
		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		if ( ! isset( $es_includes ) || $es_includes !== true ) {
			$es_includes = true;
		}
		$es_desc  = $instance['es_desc'];		
		$es_name  = $instance['enable_name'];
		$es_group = $instance['es_group'];
		$es_pre   = $instance['es_pre'];
		$name_placeholder  = $instance['name'];
		$email_placeholder  = $instance['email'];
		$button_text = $instance['button_text'];
		$button_style = $instance['button_style'];
		$button_style = str_replace("btn-","",$button_style).'-color';
		$form_class = 'newsletter-widget-form newsletter-form footer-form';
		
		$instance['form_class'] = $form_class;	
		$instance['email_placeholder'] = $instance['email'];	
		$instance['name_placeholder'] = $instance['name'];	
		$instance['name_visible'] = false;
		$instance['fields_before'] = '<div class="input-group">';	
		$instance['fields_after'] = '</div>';	
		$instance['email_field_before'] = '';	
		$instance['email_field_after'] = '';
		$instance['form_button_style'] = 'icon';
		$instance['form_button_icon'] = $button_text;

		ob_start();
		self::render_form($instance);
		return $es_form = ob_get_clean();		

	}

	public static function render_form( $data ) {

		
		// Compatibility for GDPR
		$active_plugins = get_option( 'active_plugins', array() );
		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}
		$form_wrapper_class = array('emaillist');	
		$form_class = array('es_subscription_form');	
		
		$form_id       = ! empty( $data['form_id'] ) ? $data['form_id'] : 0;
		$desc          = ! empty( $data['desc'] ) ? $data['desc'] : 0;
		$before          = ! empty( $data['fields_before'] ) ? $data['fields_before'] : '';
		$after          = ! empty( $data['fields_after'] ) ? $data['fields_after'] : '';

		$form_class[] = ! empty( $data['form_class'] ) ? $data['form_class'] : '';
		$form_wrapper_class[] = ! empty( $data['form_wrapper_class'] ) ? $data['form_wrapper_class'] : '';
		

		

		$current_page     = get_the_ID();
		$current_page_url = get_the_permalink( get_the_ID() );

		$unique_id = uniqid('perch_');
		$hp_style  = "position:absolute;top:-99999px;" . ( is_rtl() ? 'right' : 'left' ) . ":-99999px;z-index:-99;";
		$nonce     = wp_create_nonce( 'es-subscribe' );

		$form_wrapper_class = array_filter($form_wrapper_class);
		$form_class = array_filter($form_class);

		// Form html
		$form_html = '<input type="hidden" name="form_id" value="' . $form_id . '" />';
		?>

        <div class="<?php echo implode(' ', $form_wrapper_class) ?>">
            <form action="#" method="post" class="<?php echo implode(' ', $form_class) ?>" id="es_subscription_form_<?php echo $unique_id; ?>">
				<?php if ( $desc != "" ) { ?>
                    <div class="es_caption"><?php echo $desc; ?></div>
				<?php } ?>
				<?php echo $before; ?>
				<?php echo self::newsletter_form_name_field($data); ?>
				<?php echo self::newsletter_form_email_field($data); ?>
				<?php echo self::newsletter_form_list_field($data); ?>
				<?php echo $form_html; ?>

                <input type="hidden" name="es_email_page" value="<?php echo $current_page; ?>"/>
                <input type="hidden" name="es_email_page_url" value="<?php echo $current_page_url; ?>"/>
                <input type="hidden" name="status" value="Unconfirmed"/>
                <input type="hidden" name="es-subscribe" id="es-subscribe" value="<?php echo $nonce; ?>"/>
                <label style="<?php echo $hp_style; ?>"><input type="text" name="es_hp_<?php echo wp_create_nonce( 'es_hp' ); ?>" class="es_required_field" tabindex="-1" autocomplete="off"/></label>
				<?php do_action( 'es_after_form_fields' ) ?>
				<?php if ( ( in_array( 'gdpr/gdpr.php', $active_plugins ) || array_key_exists( 'gdpr/gdpr.php', $active_plugins ) ) ) {
					echo GDPR::consent_checkboxes();
				} ?>

				<?php echo self::newsletter_form_button_field($data, $unique_id); ?>

				<?php echo $after; ?>
				
                <span class="es_spinner_image" id="spinner-image"><i class="fa fa-spinner fa-spin fa-lg"></i></span>

            </form>

            <span class="es_subscription_message success" id="es_subscription_message_<?php echo $unique_id; ?>"></span>
        </div>

		<?php
	}

	public static function newsletter_form_list_field($data){
		$show_list     = ! empty( $data['list_visible'] ) ? $data['list_visible'] : false;
		$list_ids      = ! empty( $data['lists'] ) ? $data['lists'] : array();
		$list          = ! empty( $data['list'] ) ? $data['list'] : 0;

		// Lists
		if ( ! empty( $list_ids ) && $show_list ) {
			$lists_id_name_map = ES_DB_Lists::get_list_id_name_map();
			$list_html         = self::prepare_lists_checkboxes( $lists_id_name_map, $list_ids );
		} elseif ( ! empty( $list_ids ) && ! $show_list ) {
			$list_html = '';
			foreach ( $list_ids as $id ) {
				$list_html .= '<input type="hidden" name="lists[]" value="' . $id . '" />';
			}
		} elseif ( is_numeric( $list ) ) {
			$list_html = '<input type="hidden" name="lists[]" value="' . $list . '" />';
		} else {
			$list_data = ES_DB_Lists::get_list_by_name( $list );
			if ( empty( $list_data ) ) {
				$list_id = ES_DB_Lists::add_list( $list );
			} else {
				$list_id = $list_data['id'];
			}

			$list_html = '<input type="hidden" name="lists[]" value="' . $list_id . '" />';
		}

		$name_html = apply_filters( 'perch_modules/newsletter_form/list_field', $list_html, $data );

		return $list_html;
	}


	public static function prepare_lists_checkboxes( $lists, $list_ids = array(), $columns = 3, $selected_lists = array() ) {

		$lists_html = '<div><table><tr>';
		$i          = 0;
		foreach ( $lists as $list_id => $list_name ) {
			if ( $i != 0 && ( $i % $columns ) === 0 ) {
				$lists_html .= "</tr><tr>";
			}

			if ( in_array( $list_id, $list_ids ) ) {
				if ( in_array( $list_id, $selected_lists ) ) {
					$lists_html .= '<td><label><input type="checkbox" name="lists[]" checked="checked" value="' . $list_id . '" />' . $list_name . '</label></td>';
				} else {
					$lists_html .= '<td><label><input type="checkbox" name="lists[]" value="' . $list_id . '" />' . $list_name . '</label></td>';
				}
				$i ++;
			}
		}
		$lists_html .= '</tr></table></div>';

		return $lists_html;
	}

	public function create_newsletter_group(){	

		$theme = wp_get_theme();
		$title = $theme->get( 'Name' );
		if( is_perch_modules_supported_theme() ){

			global $wpdb;
			$table = "{$wpdb->prefix}ig_lists";
			$data = array(
				'name' => $title,
				'slug' => sanitize_title($title),
				'created_at' => current_time( 'mysql' )
			);

			$count = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE name = '$title'");
			if( $count == 0 ) $wpdb->insert( $table, $data );
		}

	}

	
}
new PerchNewsletter();