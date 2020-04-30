<?php
/**
 * @author    ThemePunch <info@themepunch.com>
 * @link      https://www.themepunch.com/
 * @copyright 2019 ThemePunch
 */

if(!defined('ABSPATH')) exit();

class RevSliderElementorWidget extends \Elementor\Widget_Shortcode {

	public function get_name() {
		
		return 'slider_revolution';
		
	}

	public function get_title() {
		
		return 'Slider Revolution';
		
	}

	public function get_icon() {
		
		return 'fa fa-refresh';
		
	}

	public function get_categories() {
		
		return array('general');
		
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'content_section',
			array(
				'label' => 'ThemePunch',
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);
		
		$this->add_control(
			'revslidertitle',
			array(
				'label' => __( 'Slider Revolution:', 'revslider' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '',
				'default' => '',
				'event' => 'themepunch.selectslider',
			)
		);
		
		$this->add_control(
			'shortcode',
			array(
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'dynamic' => ['active' => true],
				'placeholder' => '',
				'default' => '',
			)
		);
		
		$this->add_control(
			'select_slider',
			array(
				'type' => \Elementor\Controls_Manager::BUTTON,
				'button_type' => 'default',
				'text' => __( 'Select / Edit Slider', 'revslider' ),
				'event' => 'themepunch.selectslider',
			)
		);

		$this->end_controls_section();
		
	}

	protected function render() {
		
		$shortcode = $this->get_settings_for_display( 'shortcode' );
		$shortcode = do_shortcode( shortcode_unautop( $shortcode ) );
		
		// hack to make sure object library only opens when the user manually adds a slider to the page
		if(empty($shortcode)) {
		?>
		<script type="text/javascript">window.parent.elementorSelectRevSlider();</script>
		<?php
		}
		?>
		<div class="elementor-shortcode"><?php echo $shortcode; ?></div>
		<?php
	}
	

}