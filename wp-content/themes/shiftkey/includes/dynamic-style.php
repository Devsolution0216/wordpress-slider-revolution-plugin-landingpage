<?php
function shiftkey_dynamic_general_style_css() {
    $primary_color   = shiftkey_primary_color();
    $secondary_color = shiftkey_secondary_color();
    $dark_color      = shiftkey_dark_color();
    $grey_color      = shiftkey_grey_color();
    $lightgrey_color = shiftkey_light_grey_color();

    $css             = '.wpb-js-composer .vc_tta-tabs.vc_tta-tabs-position-top.vc_tta-style-shiftkey .vc_tta-tabs-list .vc_active a{background-color: ' . esc_attr($primary_color) . ';}
  .wpb-js-composer .vc_tta-tabs.vc_tta-tabs-position-top.vc_tta-style-shiftkey .vc_tta-tabs-list li a span{color: ' . esc_attr($primary_color) . ';} ';
    $css .= '   
    .bg-dark { background-color: ' . esc_attr($dark_color) . ' !important; }
    .bg-grey { background-color: ' . esc_attr($grey_color) . '; }
    .bg-lightgrey { background-color: ' . esc_attr($lightgrey_color) . '; }

    .cssload-ball,
    .page-link:hover,
    .page-link:focus,
    .page-item.active .page-link,
    .primary-theme .page-link:hover,
    .primary-theme .page-link:focus,
    .primary-theme .page-item.active .page-link,
    #stlChanger .chBut,
    .bg-primary-color,
    .btn-primary-color,
    .white-color .btn-primary-color,
    .primary-color-hover:hover,
    .white-color .primary-color-hover:hover,
    .video-btn.play-icon-theme,
    .video-btn.play-icon-primary,
    .video-btn.play-icon-preset,
    .primary-theme .sbox-7-icon,
    .primary-theme.nav-pills .nav-link.active, 
    .primary-theme.nav-pills .show > .nav-link,
    .cssload-loader-inner,    
    .bg-preset, .bg-primary,
    .bg-theme { background-color: ' . esc_attr($primary_color) . '; }

    .primary-color,
    .primary-color h2, 
    .primary-color h3, 
    .primary-color h4, 
    .primary-color h5, 
    .primary-color h6, 
    .primary-color p, 
    .primary-color a, 
    .primary-color li,
    .primary-color i, 
    .primary-color span,
    .list-group-item.active h5,
    .primary-theme.nav-pills .nav-link span,
    .primary-theme .pricing-plan h5,
    .primary-theme ul.features li i,
    .single-post-txt p a,
    .terms-box p a,
    .primary-theme p.post-meta span,
    #content-3 .list-group-item.active h5,
    .theme-color, .theme-text, .preset-color, .preset-text,
    .navbar.scroll.navbar-light .rose-hover .navbar-nav .nav-link:hover, 
    .navbar.scroll.navbar-dark .rose-hover .navbar-nav .nav-link:hover,
    .wpb-js-composer .vc_tta-tabs.vc_tta-tabs-position-top.vc_tta-style-shiftkey .vc_tta-tabs-list li a span,
    .theme-color, .theme-color h2, .theme-color h3, .theme-color h4, .theme-color h5, .theme-color h6, .theme-color p, .theme-color a, .theme-color li, .white-color .theme-color, .theme-color span,
    a.theme-hover:hover { 
      color: ' . esc_attr($primary_color) . '; 
    }

    .portfolio-filter.theme-btngroup .btn-group > .btn.active, 
    .portfolio-filter.theme-btngroup .btn-group > .btn.focus,
    .wpb-js-composer .vc_tta-tabs.vc_tta-tabs-position-top.vc_tta-style-shiftkey .vc_tta-tabs-list .vc_active a{ background-color: ' . esc_attr($primary_color) . '; border-color: ' . esc_attr($primary_color) . '; }
    .btn-theme{background-color: ' . esc_attr($primary_color) . ';border: 2px solid ' . esc_attr($primary_color) . ';}
    
    .pricing-plan.theme-border{ border-color: ' . esc_attr($primary_color) . ' }   
    
    .primary-theme .sbox-7-icon,
    .theme-progress .progress-bar {  background-color: ' . esc_attr($primary_color) . '; }
    .white-color .theme-icon span, .theme-icon span,
    .theme-hover:hover .grey-icon span { color: ' . esc_attr($primary_color) . '; }
    .btn.btn-simple:hover,
    .btn.btn-simple:focus,
    .portfolio-filter button.is-checked, .bg-dark .portfolio-filter button.is-checked,
    .widget_rss cite,.recentcomments .comment-author-link, .recentcomments .comment-author-link a,.theme-color,
    .theme-color h2, .theme-color h3,.theme-color h4, .theme-color h5, .theme-color h6, .theme-color p, .theme-color a, 
    .theme-color li, .theme-color i, .white-color .theme-color,
    .hover-menu .collapse.rose-hover ul ul > li:hover > a, 
    .navbar .rose-hover .show .dropdown-menu > li > a:focus, 
    .navbar .rose-hover .show .dropdown-menu > li > a:hover, 
    .hover-menu .collapse.rose-hover ul ul ul > li:hover > a{ 
      color: ' . esc_attr($primary_color) . '; 
    }    
    .woocommerce div.product .woocommerce-tabs ul.tabs li.active a,
    .wpb-js-composer .vc_tta-tabs.vc_tta-tabs-position-top.vc_tta-style-shiftkey-style2 .vc_tta-tabs-list .vc_active a,
    .navbar-expand-lg .nl-simple a::before{
      background-color: ' . esc_attr($primary_color) . ';
    }
    .page-link:hover,
    .page-link:focus,
    .page-item.active .page-link,
    .primary-theme .page-link:hover,
    .primary-theme .page-link:focus,
    .primary-theme .page-item.active .page-link,
    .primary-theme.nav-pills .nav-link.active, 
    .primary-theme.nav-pills .show > .nav-link,
    .btn-primary-color,   
    .cssload-loader,
    .white-color .btn-primary-color,
    .primary-color-hover:hover,
    .white-color .primary-color-hover:hover,
    .video-btn.play-icon-theme,
    #stlChanger .chBut,
    .video-btn.play-icon-primary,
    .video-btn.play-icon-preset,
    .comment-form .form-control:focus,
    .woocommerce button.button.alt.single_add_to_cart_button,
    .portfolio-filter button.is-checked, .bg-dark .portfolio-filter button.is-checked{
      border-color: ' . esc_attr($primary_color) . ';
    }
    .quote.primary-theme p{
        border-left-color: ' . esc_attr($primary_color) . ';
    }
    .primary-theme .pricing-table.highlight{
        border-top-color: ' . esc_attr($primary_color) . ';
    }
    .blog-post-txt .post-meta a:hover,
    .blog-post-txt .post-meta a:focus{
      color: ' . esc_attr($dark_color) . ';
    }
    ';
    $darkcolorArr    = shiftkey_default_dark_color_classes(array(
        'prefix' => 'btn-'
    ));
    $darkcolortraArr = shiftkey_default_dark_color_classes(array(
        'prefix' => 'btn-tra-'
    ));
    $colors_arr      = shiftkey_default_color_classes();
    foreach ($colors_arr as $key => $value) {
        $color            = $value['value'];
        //check dark color
        $btncolorcss      = '';
        $btn_color        = $color;
        $button_style     = 'btn-' . $key;
        $button_tra_style = 'btn-tra-' . $key;
        if (in_array($button_style, $darkcolorArr))
            $btncolorcss = 'color: #fff;';
        if (!in_array($button_tra_style, $darkcolortraArr)){
            $btn_color = '#333';
            $btncolorcss = 'color: #fff !important; ';
        }
            
        $css .= "
            .fbox-3.{$key}-hover:hover {
                border-bottom: 1px solid {$color};
            }
            .fbox-3.{$key}-hover:hover .b-icon span,
            .bg-{$key} { background-color: {$color}; } 
            .underline-{$key} { 
              background-image: linear-gradient(120deg, {$color} 0%, {$color} 90%); 
              background-repeat: no-repeat;
              background-size: 100% 0.22em;
              background-position: 0 105%; 
            }";
        $color = isset($value['color']) ? $value['color'] : $value['value'];
        $css .= "
            .has-{$key}-color.has-text-color,
            .{$key}-icon [class^='ti-'], .{$key}-icon [class*=' ti-'],
            .{$key}-color-icon [class^='ti-'], .{$key}-color-icon [class*=' ti-'],
            .{$key}-nav .slick-prev::before, 
            .{$key}-nav .slick-next::before,
            .navbar.{$key}-hover .navbar-nav .nav-link:focus, 
                      .navbar.{$key}-hover .navbar-nav .nav-link:hover, 
                      .modal-video .{$key}-color,
                      .{$key}-color,
                      .{$key}-color h2, 
                      .{$key}-color h3, 
                      .{$key}-color h4, 
                      .{$key}-color h5, 
                      .{$key}-color h6, 
                      .{$key}-color p, 
                      .{$key}-color a, 
                      .{$key}-color li,
                      .{$key}-color i, 
                      .white-color .{$key}-color,
                      .pbox-icon [class*='flaticon-'].{$key}-color:before,
                      span.section-id.{$key}-color,
                      .{$key}-color p{ color: {$color}; }";
        $css .= "             
            .navbar .nav-button .btn-tra-{$key}:hover,
            .navbar .nav-button .btn-tra-{$key}:focus,            
            .btn-tra-{$key}:hover,
            .btn-tra-{$key}:focus{
              background-color: {$btn_color} !important;               
              border-color: {$btn_color} !important;               
              {$btncolorcss}
            }";
        $css .= "  
            .is-style-outline .has-{$key}-background-color,            
            .btn-tra-{$key}{
              background-color: transparent; 
              border-color: {$color};
            }";
        $css .= ".{$key}-icon, .{$key}-icon [class^='flaticon-']::before {color: {$color};}";
        $css .= ".navbar.scroll.{$key}-scroll{background-color: {$color} !important;}";
        $css .= " 
            .btn-{$key},       
            .header-socials a:focus,
            .header-socials a:hover, 
            .scrollbg-dark.scroll .header-socials a:focus,
            .scrollbg-dark.scroll .header-socials a:hover,
            .video-btn.play-icon-{$key}, 
            .box-rounded.box-rounded-{$key}{
              border-color: {$color};
            }";
        $css .= "
            .btn-{$key},
            .has-{$key}-koromiko-background-color,
            .has-{$key}-background-color,
            .header-socials a:focus,
            .header-socials a:hover, 
            .fbox-3:hover .{$key}-color-box .box-line,
            .fbox-3:hover .{$key}-icon [class^='ti-'], .fbox-3:hover .{$key}-icon [class*=' ti-'],
            .fbox-3:hover .{$key}-color-icon [class^='ti-'], .fbox-3:hover .{$key}-color-icon [class*=' ti-'],
            .video-btn.play-icon-{$key},
            .video-1 .video-btn.play-icon-{$key},
            .{$key}-nav.perch-vc-carousel .owl-nav [class*='owl-']:hover,
            .{$key}-nav.perch-vc-carousel .owl-dots .owl-dot.active span,
            .perch-vc-carousel.{$key}-nav .slick-dots li.slick-active button::before,
            .vc-bg-{$key} .shiftkey-vc .wpb_element_wrapper{ background-color: {$color} }
            ";
    }
    return shiftkey_compress($css);
}
function shiftkey_typography_style_css() {
    $css = 'html, body, .primary-font{ ' . shiftkey_typography_css('body') . ' }';
    $css .= '.secondary-font,
  h1, h2, h3, h4, h5, h6, .btn, 
  .modal-video a, 
  span.section-id, 
  .navbar-expand-md .navbar-nav .nav-link, 
  .hero-section .newsletter-form-notification,
  .hero-section .newsletter-form label.valid,
  .hero-section .newsletter-form label.error,
  .processbar li:before, 
  a.process-link,
  .iblock p,
  .statistic-block p,
  .testimonial-avatar p,
  .pricing-table span.price,
  .pricing-table span.price,
  .popular-post a,
  .blog-post-txt p.post-meta,
  .comment-form-msg .error,
  .comment-form-msg .loading, 
  .contact-form .loading,
  .breadcrumb-item a,
  .breadcrumb-item.active,
  p.p-notice,
  .sending-msg .loading{ 
    ' . shiftkey_typography_css('heading') . ' 
  }';
    $css .= 'h1{ ' . shiftkey_typography_css('h1') . ' }';
    $css .= 'h2{ ' . shiftkey_typography_css('h2') . ' }';
    $css .= 'h3{ ' . shiftkey_typography_css('h3') . ' }';
    $css .= 'h4{ ' . shiftkey_typography_css('h4') . ' }';
    $css .= 'h5{ ' . shiftkey_typography_css('h5') . ' }';
    $css .= 'h6{ ' . shiftkey_typography_css('h6') . ' }';
    $typoFields = array(
        'p',
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6'
    );
    $typoSizes  = array(
        'xs',
        'sm',
        'md',
        'lg',
        'xl',
        'huge'
    );
    foreach ($typoFields as $tag) {
        foreach ($typoSizes as $size) {
            $css .= "{$tag}.{$tag}-{$size}, {$tag}.{$tag}-{$size} > a { " . shiftkey_typography_css($tag . '-' . $size) . ' }';
        }
    }
    $css .= '.navbar-light .logo-text, .navbar-dark .logo-text{ ' . shiftkey_typography_css('logo_text_typo') . ' }';
    $css .= '.navbar-expand-md .navbar-nav .nav-link{ ' . shiftkey_typography_css('menu_a') . ' }';
    $css .= '.dropdown-item, .dropdown-menu li a{ ' . shiftkey_typography_css('submenu_a') . ' }';
    $css .= '.dropdown-item li a, .dropdown-menu ul li a{ ' . shiftkey_typography_css('submenu_ul_a') . ' }';
    return shiftkey_compress($css);
}
function shiftkey_bootstrap_style_css() {
    $primary_color   = shiftkey_primary_color();
    $secondary_color = shiftkey_secondary_color();
    $dark_color      = shiftkey_dark_color();
    $css           = '
        .btn-secondary:hover,
        .btn{ 
            background-color:' . esc_attr($primary_color) . ';
            border-color:' . esc_attr($primary_color) . ';
        }
        .btn-primary:hover{ 
            background-color: ' . esc_attr($secondary_color) . ';
            border-color: ' . esc_attr($secondary_color) . '; 
        }
    ';
    return shiftkey_compress($css);
}
function shiftkey_woocommerce_general_style_css() {
    $primary_color   = shiftkey_primary_color();
    $secondary_color = shiftkey_secondary_color();
    $dark_color      = shiftkey_dark_color();
    $grey_color      = shiftkey_grey_color();
    $lightgrey_color = shiftkey_light_grey_color();

    $output          = '';
    $output .= ' 
    .woocommerce-info > a:hover,
    .woocommerce-info::before,
    .woocommerce-MyAccount-content p a,
    .page-content .woocommerce-MyAccount-navigation ul .active a,
    .woocommerce .single-widget a:hover,
    .woocommerce .single-widget a:focus,
    .product_meta a,
    .order-total .amount,
    .product-name strong,
    .single-product .summary .yith-wcwl-add-to-wishlist a,   
    .woocommerce div.product p.price, 
    .woocommerce div.product span.price{
        color: ' . $primary_color . ';
    }
    .loop-item-hover-in .btn:hover,
    .loop-item-hover-in .btn:focus,
    #headersearch .caret,
    .woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
    .woocommerce .widget_price_filter .ui-slider .ui-slider-range,
    .woocommerce #respond input#submit:hover, 
    .woocommerce a.button:hover, 
    .woocommerce button.button:hover, 
    .woocommerce input.button:hover,
    .woocommerce div.product .woocommerce-tabs ul.tabs li.active a,
    .product-inner-buttons .yith-wcwl-wishlistaddedbrowse a,
    .product-inner-buttons .yith-wcwl-wishlistexistsbrowse a,
    .product-inner-buttons .yith-wcqv-button:hover,
    .product-inner-buttons .yith-wcqv-button:focus,
    .woocommerce #respond input#submit.alt, 
    .woocommerce a.button.alt, 
    .woocommerce button.button.alt, 
    .woocommerce input.button.alt{
        background-color: ' . $primary_color . ';
    }
    .loop-item-hover-in .btn:hover,
    .loop-item-hover-in .btn:focus,
    .product-item .product-inner-buttons .yith-wcwl-wishlistaddedbrowse a,
    .product-item .product-inner-buttons .yith-wcwl-wishlistexistsbrowse a,
    .product-inner-buttons .yith-wcqv-button:hover,
    .product-inner-buttons .yith-wcqv-button:focus{
      border-color: ' . $primary_color . ';
    }
    .nav-item .cart-contents .cart-contents-count,
    .woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content,
    .woocommerce #respond input#submit.alt:hover, 
    .woocommerce a.button.alt:hover, 
    .woocommerce button.button.alt:hover, 
    .woocommerce input.button.alt:hover{
        background-color: ' . $secondary_color . ';
    }
    .woocommerce-error, 
    .woocommerce-info, 
    .woocommerce-message{
        background-color: ' . $lightgrey_color . ';
    }    
    ';
    return shiftkey_compress($output);
}
function shiftkey_background_css($option_id, $selector = '') {
    $background = shiftkey_get_option($option_id, array());
    $output     = '';
    if (!empty($background)) {
        $background_color      = (isset($background['background-color']) && ($background['background-color'] != '')) ? 'background-color:' . $background['background-color'] . '!important ; ' . "\n" : '';
        $background_image      = (isset($background['background-image']) && ($background['background-image'] != '')) ? 'background-image: url(' . esc_url($background['background-image']) . ');' . "\n" : '';
        $background_repeat     = (isset($background['background-repeat']) && ($background['background-repeat'] != '')) ? 'background-repeat: ' . $background['background-repeat'] . ';' . "\n" : '';
        $background_positon    = (isset($background['background-position']) && ($background['background-position'] != '')) ? 'background-position:' . $background['background-position'] . ';' . "\n" : '';
        $background_attachment = (isset($background['background-attachment']) && ($background['background-attachment'] != '')) ? 'background-attachment:' . $background['background-attachment'] . ';' . "\n" : '';
        $background_size       = (isset($background['background-size']) && ($background['background-size'] != '')) ? 'background-size: ' . $background['background-size'] . ';' . "\n" : '';
        $output .= "\n" . esc_attr($selector) . ' { ' . "\n" . $background_color . $background_image . $background_repeat . $background_attachment . $background_positon . $background_size . '}' . "\n";
    }
    return $output;
}
function shiftkey__background_css($background, $selector = '') {
    $output = '';
    if (!empty($background)) {
        $background_color      = (isset($background['background-color']) && ($background['background-color'] != '')) ? 'background-color:' . $background['background-color'] . ';' . "\n" : '';
        $background_image      = (isset($background['background-image']) && ($background['background-image'] != '')) ? 'background-image: url(' . esc_url($background['background-image']) . ');' . "\n" : '';
        $background_repeat     = (isset($background['background-repeat']) && ($background['background-repeat'] != '')) ? 'background-repeat: ' . $background['background-repeat'] . ';' . "\n" : '';
        $background_positon    = (isset($background['background-position']) && ($background['background-position'] != '')) ? 'background-position:' . $background['background-position'] . ';' . "\n" : '';
        $background_attachment = (isset($background['background-attachment']) && ($background['background-attachment'] != '')) ? 'background-attachment:' . $background['background-attachment'] . ';' . "\n" : '';
        $background_size       = (isset($background['background-size']) && ($background['background-size'] != '')) ? 'background-size: ' . $background['background-size'] . ';' . "\n" : '';
        $output .= "\n" . esc_attr($selector) . ' { ' . "\n" . $background_color . $background_image . $background_repeat . $background_attachment . $background_positon . $background_size . '}' . "\n";
    }
    return $output;
}
function shiftkey_spacing_option($option_id) {
    $spacing = shiftkey_get_option($option_id, array());
    if (!empty($spacing)) {
        $unit = (isset($spacing['unit']) && ($spacing['unit'] != '')) ? $spacing['unit'] : 'px';
        return (isset($spacing['top']) ? $spacing['top'] . $unit : 0) . ' ' . (isset($spacing['right']) ? $spacing['right'] . $unit : 0) . ' ' . (isset($spacing['bottom']) ? $spacing['bottom'] . $unit : 0) . ' ' . (isset($spacing['left']) ? $spacing['left'] . $unit : 0);
    } else {
        return '';
    }
}
function shiftkey_typography_css($option_id) {
    $typography = shiftkey_get_option($option_id, array());
    $css        = '';
    if (!empty($typography) && is_array($typography)):
        foreach ($typography as $key => $value) {
            $css .= (($value != '') && ($key != 'google')) ? $key . ': ' . $value . '; ' : '';
        }
    endif;
    return $css;
}
function shiftkey_custom_bg_css($selector = '', $prefix = '', $args = array()) {
    $property = '';
    if ($selector == '')
        return false;
    if ($prefix == '')
        return false;
    $bg_class = esc_attr(shiftkey_get_option($prefix . '_class'));
    $bg_class = apply_filters('shiftkey/' . $prefix . '_class', $bg_class);
    $colors   = array();
    if ($bg_class == 'bg-custom-gradient') {
        $selector .= '.bg-custom-gradient';
        $colors = shiftkey_get_option($prefix . '_gradient');
        $colors = apply_filters('shiftkey/' . $prefix . '_gradient', $colors);
        if (empty($colors))
            return false;
        $type = shiftkey_get_option($prefix . '_gradient_type');
        $type = apply_filters('shiftkey/' . $prefix . '_gradient_type', $type);
        extract(shortcode_atts(array(
            'position' => ''
        ), $args));
        if ($position == '') {
            $position = ($type == 'linear') ? 'to right' : 'circle';
        }
        $from     = $colors['from'];
        $to       = $colors['to'];
        $property = 'background-image: ' . $type . '-gradient(' . $position . ', ' . $from . ', ' . $to . ');';
    }
    if ($bg_class == 'bg-custom') {
        $selector .= '.bg-custom';
        $color    = shiftkey_get_option($prefix . '_color');
        $color    = apply_filters('shiftkey/' . $prefix . '_color', $color);
        $property = ($color != '') ? 'background-color: ' . $color . ';' : '';
    }
    if ($property != '') {
        return $selector . ' { ' . $property . ' }';
    }
}
/**
 * Returns CSS for the color schemes.
 *
 * @param array $colors Color scheme colors.
 * @return string Color scheme CSS.
 */
function shiftkey_get_dynamic_header_css() {
    global $shiftkey_options;
    $primary_color     = shiftkey_get_option('primary_color', shiftkey_primary_color());
    $secondary_color   = shiftkey_get_option('secondary_color', shiftkey_primary_color());
    $css               = '';
    $default_header_bg = array(
        'background-color' => '',
        'background-attachment' => 'fixed',
        'background-image' => shiftkey_get_option('header_bg_img', ShiftkeyHeader::get_default_header_image()),
        'background-size' => 'cover'
    );
    $header_parallax_bg = shiftkey_get_option('header_parallax_bg', $default_header_bg);
    $header_parallax_bg = apply_filters( 'shiftkey_header_parallax_bg', $header_parallax_bg );

    $header_bg         = apply_filters('shiftkey_header_image_url', $default_header_bg);
    $css .= shiftkey_background_css('body_background', 'body');
    $css .= shiftkey_background_css('footer_background', '#footer-1');
    if ($shiftkey_options['header_parallax_switch']) {
        $css .= '#blogs-page.page-hero-section .parallax-inner{opacity: ' . $shiftkey_options['header_parallax_opacity'] . '}';
        $css .= shiftkey__background_css($header_parallax_bg, '#blogs-page.page-hero-section .parallax-inner');
    }
    $css .= shiftkey_custom_bg_css('#blogs-page.page-hero-section', 'header_bg');
    $css .= shiftkey_custom_bg_css('.navbar', 'nav_bg');
    $css .= shiftkey_custom_bg_css('footer.footer', 'footer_bg');

    $css .= shiftkey_custom_bg_css('.newsletter-section', 'newsletter_bg');    
    if ($shiftkey_options['newsletter_parallax_switch']) {
        $css .= '.newsletter-section .parallax-inner{opacity: ' . $shiftkey_options['newsletter_parallax_opacity'] . '}';
    }

    $css .= shiftkey_custom_bg_css('.cta-section', 'cta_bg');
    if ($shiftkey_options['cta_parallax_switch']) {
        $css .= '.cta-section .parallax-inner{opacity: ' . $shiftkey_options['cta_parallax_opacity'] . '}';
    }



    $arr = shiftkey_get_option('container_width', array(
        '1140',
        'px'
    ));
    $css .= '@media (min-width: 1200px) { .container {  max-width: ' . esc_attr($arr[0]) . esc_attr($arr[1]) . '; } }';
   
    $overlay_opacity = shiftkey_get_option('overlay_opacity', 0);
    $overlay_type    = apply_filters('shiftkey_breadcrumbs_overlay_type', 'light');
    $overlay_type    = shiftkey_get_option('breadcrumbs_overlay_type', $overlay_type);
    $color           = shiftkey_hex2rgb('#000', false);
    if ($overlay_type == 'light') {
        $color = shiftkey_hex2rgb('#fff', false);
    }
    if ($overlay_type == 'theme') {
        $color = shiftkey_hex2rgb($primary_color, false);
    }
    $css .= shiftkey_spacing_css_style();
    return shiftkey_compress($css);
}