<?php
function shiftkey_single_product_options( $metabox = false, $args = array( ) ) {
	$options = array();

	$options = array(
        array(
             'id' => 'single_product_header',
            'label' => esc_attr__( 'Single product header', 'shiftkey' ),
            'desc' => '',
            'std' => 'off',
            'type' => 'on-off',
            'section' => 'woo_options' 
        ),
        array(
             'id' => 'product_layout',
            'label' => esc_attr__( 'Product layout', 'shiftkey' ),
            'desc' => '',
            'std' => 'rs',
            'type' => 'radio-image',
            'section' => 'woo_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '',
            'class' => '',
            'condition' => '',
            'operator' => 'and',
            'choices' => array(
                 array(
                     'value' => 'full',
                    'label' => esc_attr__( 'Full width', 'shiftkey' ),
                    'src' => SHIFTKEY_URI . '/admin/assets/images/layout/full-width.png' 
                ),
                array(
                     'value' => 'ls',
                    'label' => esc_attr__( 'Left sidebar', 'shiftkey' ),
                    'src' => SHIFTKEY_URI . '/admin/assets/images/layout/left-sidebar.png' 
                ),
                array(
                     'value' => 'rs',
                    'label' => esc_attr__( 'Right sidebar', 'shiftkey' ),
                    'src' => SHIFTKEY_URI . '/admin/assets/images/layout/right-sidebar.png' 
                ) 
            ) 
        ),
        array(
             'id' => 'product_layout_sidebar',
            'label' => esc_attr__( 'Product Sidebar', 'shiftkey' ),
            'desc' => '',
            'std' => 'sidebar-product',
            'type' => 'sidebar-select',
            'section' => 'woo_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '',
            'class' => '',
            'condition' => 'product_layout:not(full)',
            'operator' => 'and' 
        ),
        array(
             'id' => 'single_image_width',
            'label' => esc_attr__( 'Single Product Image Width', 'shiftkey' ),
            'desc' => esc_attr__( 'This size used in single product page.', 'shiftkey' ),
            'std' => '600',
            'type' => 'numeric-slider',
            'section' => 'woo_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '400,1200,5',
            'class' => '',
            'condition' => '',
            'operator' => 'and' 
        ),
        array(
             'id' => 'single_image_height',
            'label' => esc_attr__( 'Single Product Image height', 'shiftkey' ),
            'desc' => esc_attr__( 'This size used in single product page.', 'shiftkey' ),
            'std' => '700',
            'type' => 'numeric-slider',
            'section' => 'woo_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '400,1000,5',
            'class' => '',
            'condition' => '',
            'operator' => 'and' 
        ),
        array(
             'id' => 'related_product_display',
            'label' => esc_attr__( 'Related product show in single product', 'shiftkey' ),
            'desc' => '',
            'std' => 'on',
            'type' => 'on-off',
            'section' => 'woo_options',
            'condition' => '',
            'operator' => 'and' 
        ), 
        array(
             'id' => 'related_product_title',
            'label' => esc_attr__( 'Related products title', 'shiftkey' ),
            'desc' => '',
            'std' => 'Keep Shopping: Related Products',
            'type' => 'text',
            'section' => 'woo_options',
            'condition' => 'related_product_display:is(on)' 
        ),
        array(
             'id' => 'related_product_loop_columns',
            'label' => esc_attr__( 'Related Products column', 'shiftkey' ),
            'desc' => '',
            'std' => '3',
            'type' => 'numeric-slider',
            'section' => 'woo_options',
            'min_max_step' => '1,4,1',
            'condition' => 'related_product_display:is(on)',
            'operator' => 'and' 
        ),
        array(
             'id' => 'related_products_per_page',
            'label' => esc_attr__( 'Related Products display', 'shiftkey' ),
            'desc' => '',
            'std' => '3',
            'type' => 'numeric-slider',
            'section' => 'woo_options',
            'min_max_step' => '2,12,1',
            'condition' => 'related_product_display:is(on)',
            'operator' => 'and' 
        ),
        
    );
    $options = apply_filters( 'shiftkey_theme_options', $options, 'blog_options' );

	if($metabox){
        return apply_filters( 'shiftkey/redux_to_metaboxes', $options);
    }else{
        return $options;
    }
}