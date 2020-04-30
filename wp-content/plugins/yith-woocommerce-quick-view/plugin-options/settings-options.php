<?php

$settings = array(

	'settings'  => array(
		
		'general-options' => array(
			'title' => __( 'General Options', 'yith-woocommerce-quick-view' ),
			'type' => 'title',
			'desc' => '',
			'id' => 'yith-wcqv-general-options'
		),

		'enable-quick-view' => array(
			'id'        => 'yith-wcqv-enable',
			'name'      => __( 'Enable Quick View', 'yith-woocommerce-quick-view' ),
            'type'      => 'yith-field',
            'yith-type' => 'onoff',
			'default'   => 'yes'
		),

		'enable-quick-view-mobile'  => array(
			'id'        => 'yith-wcqv-enable-mobile',
			'name'      => __( 'Enable Quick View on mobile', 'yith-woocommerce-quick-view' ),
			'desc'      => __( 'Enable quick view features on mobile device too', 'yith-woocommerce-quick-view' ),
            'type'      => 'yith-field',
            'yith-type' => 'onoff',
			'default'   => 'yes'
		),

		'quick-view-label'  => array(
			'id'        => 'yith-wcqv-button-label',
			'name'      => __( 'Quick View Button Label', 'yith-woocommerce-quick-view' ),
			'desc'      => __( 'Label for the quick view button in the WooCommerce loop.', 'yith-woocommerce-quick-view' ),
            'type'      => 'yith-field',
            'yith-type' => 'text',
			'default'   => __( 'Quick View', 'yith-woocommerce-quick-view' ),
		),

		'enable-lightbox' => array(
			'id'        => 'yith-wcqv-enable-lightbox',
			'name'      => __( 'Enable Lightbox', 'yith-woocommerce-quick-view' ),
			'desc'      => __( 'Enable lightbox. Product images will open in a lightbox.', 'yith-woocommerce-quick-view' ),
            'type'      => 'yith-field',
            'yith-type' => 'onoff',
			'default'   => 'yes'
		),

		'general-options-end' => array(
			'type'      => 'sectionend',
			'id'        => 'yith-wcqv-general-options'
		),

		'style-options' => array(
			'title' => __( 'Style Options', 'yith-woocommerce-quick-view' ),
			'desc'  => '',
			'type'  => 'title',
			'id'    => 'yith-wcqv-style-options'
		),

		'background-color-modal' => array(
			'name'      => __( 'Modal Window Background Color', 'yith-woocommerce-quick-view' ),
            'type'      => 'yith-field',
            'yith-type' => 'colorpicker',
			'desc'      => '',
			'id'        => 'yith-wcqv-background-modal',
			'default'   => '#ffffff'
		),

		'close-button-color' => array(
			'name'      => __( 'Closing Button Color', 'yith-woocommerce-quick-view' ),
            'type'      => 'yith-field',
            'yith-type' => 'colorpicker',
			'desc'      => '',
			'id'        => 'yith-wcqv-close-color',
			'default'   => '#cdcdcd'
		),

		'close-button-color-hover' => array(
			'name'      => __( 'Closing Button Hover Color', 'yith-woocommerce-quick-view' ),
            'type'      => 'yith-field',
            'yith-type' => 'colorpicker',
            'desc'      => '',
			'id'        => 'yith-wcqv-close-color-hover',
			'default'   => '#ff0000'
		),

		'style-options-end' => array(
			'type'  => 'sectionend',
			'id'    => 'yith-wcqv-style-options'
		),


	)
);

return apply_filters( 'yith_wcqv_panel_settings_options', $settings );