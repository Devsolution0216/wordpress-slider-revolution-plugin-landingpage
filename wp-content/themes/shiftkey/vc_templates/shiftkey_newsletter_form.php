<?php
$args = shiftkey_newsletter_form_shortcode_vc(true);
$atts = shortcode_atts( $args, $atts);
extract($atts);
$paramsArr = (function_exists('vc_param_group_parse_atts'))? vc_param_group_parse_atts($params) : array();
?>
                    
    <form class="newsletter-form es_shortcode_form" data-es_form_id="es_shortcode_form">                        
    <?php                
        $args = array();
        $group = 'shiftkey';
        $args['placeholder'] = esc_attr($placeholder);
        $args['button_text'] = 'fas fa-arrow-right';
        $args['group'] = esc_attr($group);
        shiftkey_es_subbox( $args );                
        ?>                                  
    </form>            

    <?php if( !empty($paramsArr) ): ?>              
    <div class="hero-links">
    <?php foreach ($paramsArr as $key => $value):                   
        $link_before = isset( $value['link_before'] )? esc_attr($value['link_before']) : '';
        ?>
        <span><?php echo esc_attr($link_before);  ?>
            <?php if( isset($value['add_link']) && ($value['link_title'] != '') ):  ?>
                <a href="<?php echo isset($value['link_url'])? esc_url($value['link_url']) : '#'; ?>"><?php echo esc_attr($value['link_title']) ?></a> 
            <?php endif; ?>
            <?php echo isset( $value['title'] )? esc_attr( $value['title'] ) : ''; ?></span>    
    <?php endforeach; ?>
    </div>
    <?php endif; ?>           