<?php
function shiftkey_portfolio_options( $options = array( ) ) {
    $options = array(
        array(
             'id' => 'Portfolio_option_tab',
            'label' => esc_attr__( 'Portfolio settings', 'shiftkey' ),
            'type' => 'tab',
            'section' => 'portfolio_options',
        ),
         array(
             'id' => 'portfolio_archive',
            'label' => 'Portfolio Archive page',
            'desc' => sprintf( __( 'If archive page is not working, then save again <a href="%s" target="_blank">permalink settings</a>, For best performance use Pretty permalink(Example: Post name, Day and name etc)', 'shiftkey' ), admin_url( 'options-permalink.php' ) ),
            'std' => ( get_post_status( get_option( 'portfolio_archive_id' ) ) == 'publish' ) ? get_option( 'portfolio_archive_id' ) : '',
            'type' => 'page-select',
            'section' => 'portfolio_options',
            'rows' => '' 
        ),
        array(
             'id' => 'portfolio_single_layout',
            'label' => esc_attr__( 'Single portfolio layout', 'shiftkey' ),
            'desc' => '',
            'std' => 'full',
            'type' => 'radio-image',
            'section' => 'portfolio_options',
            'operator' => 'and',
            'choices' => array(
                 array(
                     'value' => 'full',
                    'label' => esc_attr__( 'Full width', 'shiftkey' ),
                    'src' => OT_URL . '/assets/images/layout/full-width.png' 
                ),
                array(
                     'value' => 'ls',
                    'label' => esc_attr__( 'Left sidebar', 'shiftkey' ),
                    'src' => OT_URL . '/assets/images/layout/left-sidebar.png' 
                ),
                array(
                     'value' => 'rs',
                    'label' => esc_attr__( 'Right sidebar', 'shiftkey' ),
                    'src' => OT_URL . '/assets/images/layout/right-sidebar.png' 
                ) 
            ) 
        ),
        array(
             'id' => 'portfolio_single_layout_sidebar',
            'label' => esc_attr__( 'Single portfolio Sidebar', 'shiftkey' ),
            'desc' => '',
            'std' => 'sidebar-1',
            'type' => 'sidebar-select',
            'section' => 'portfolio_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '',
            'class' => '',
            'condition' => 'portfolio_single_layout:not(full)',
            'operator' => 'and' 
        ),
        array(
             'id' => 'display_single_related_portfolio',
            'label' => esc_attr__( 'Related portfolio', 'shiftkey' ),
            'desc' => '',
            'std' => 'on',
            'type' => 'on-off',
            'section' => 'portfolio_options',
            'condition' => '',
            'operator' => 'and' 
        ),
        array(
             'id' => 'related_portfolio_title',
            'label' => esc_attr__( 'Related portfolio title', 'shiftkey' ),
            'desc' => '',
            'std' => 'Related portfolio',
            'type' => 'text',
            'section' => 'portfolio_options',
            'condition' => 'display_single_related_portfolio:is(on)',
            'operator' => 'and' 
        ),
        array(
             'id' => 'related_portfolio',
            'label' => esc_attr__( 'Related portfolio display', 'shiftkey' ),
            'min_max_step' => '-1,20,1',
            'std' => '3',
            'type' => 'numeric-slider',
            'section' => 'portfolio_options',
            'condition' => 'display_single_related_portfolio:is(on)',
            'operator' => 'and' 
        ), 
        array(
             'id' => 'Team_option_tab',
            'label' => esc_attr__( 'Team settings', 'shiftkey' ),
            'type' => 'tab',
            'section' => 'portfolio_options',
        ),       
        array(
             'id' => 'team_archive',
            'label' => 'Team Archive page',
            'desc' => sprintf( __( 'If archive page is not working, then save again <a href="%s" target="_blank">permalink settings</a>, For best performance use Pretty permalink(Example: Post name, Day and name etc)', 'shiftkey' ), admin_url( 'options-permalink.php' ) ),
            'std' => ( get_post_status( get_option( 'team_archive_id' ) ) == 'publish' ) ? get_option( 'team_archive_id' ) : '',
            'type' => 'page-select',
            'section' => 'portfolio_options',
            'rows' => '' 
        ),
        array(
             'id' => 'display_team_hiring',
            'label' => esc_attr__( 'Display team hiring', 'shiftkey' ),
            'desc' => '',
            'std' => 'on',
            'type' => 'on-off',
            'section' => 'portfolio_options',
            'condition' => '',
            'operator' => 'and' 
        ),
        array(
             'id' => 'team_hiring_title',
            'label' => esc_attr__( 'Team hiring title', 'shiftkey' ),
            'desc' => '',
            'std' => 'We Are Hiring!',
            'type' => 'text',
            'section' => 'portfolio_options',
            'condition' => 'display_team_hiring:is(on)',
            'operator' => 'and' 
        ),
        array(
             'id' => 'team_hiring_link_text',
            'label' => esc_attr__( 'Team hiring link text', 'shiftkey' ),
            'desc' => '',
            'std' => 'Become part of our team',
            'type' => 'text',
            'section' => 'portfolio_options',
            'condition' => 'display_team_hiring:is(on)',
            'operator' => 'and' 
        ),
        array(
             'id' => 'team_hiring_link',
            'label' => esc_attr__( 'Team hiring link', 'shiftkey' ),
            'desc' => '',
            'std' => '#',
            'type' => 'text',
            'section' => 'portfolio_options',
            'condition' => 'display_team_hiring:is(on)',
            'operator' => 'and' 
        ),
        array(
             'id' => 'team_single_layout',
            'label' => esc_attr__( 'Single team layout', 'shiftkey' ),
            'desc' => '',
            'std' => 'full',
            'type' => 'radio-image',
            'section' => 'portfolio_options',
            'operator' => 'and',
            'choices' => array(
                 array(
                     'value' => 'full',
                    'label' => esc_attr__( 'Full width', 'shiftkey' ),
                    'src' => OT_URL . '/assets/images/layout/full-width.png' 
                ),
                array(
                     'value' => 'ls',
                    'label' => esc_attr__( 'Left sidebar', 'shiftkey' ),
                    'src' => OT_URL . '/assets/images/layout/left-sidebar.png' 
                ),
                array(
                     'value' => 'rs',
                    'label' => esc_attr__( 'Right sidebar', 'shiftkey' ),
                    'src' => OT_URL . '/assets/images/layout/right-sidebar.png' 
                ) 
            ) 
        ),
        array(
             'id' => 'team_single_layout_sidebar',
            'label' => esc_attr__( 'Single portfolio Sidebar', 'shiftkey' ),
            'desc' => '',
            'std' => 'sidebar-page',
            'type' => 'sidebar-select',
            'section' => 'portfolio_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '',
            'class' => '',
            'condition' => 'team_single_layout:not(full)',
            'operator' => 'and' 
        ),
    );
    return apply_filters( 'shiftkey_theme_options', $options, 'portfolio_options' );
}
?>