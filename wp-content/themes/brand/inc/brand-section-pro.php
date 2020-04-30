<?php
/**
 * Pro customizer section.
 *
 * @since  1.6.3
 * @access public
 */
class Brand_Customize_Section_Pro extends WP_Customize_Section {

	/**
	 * The type of customize section being rendered.
	 *
	 * @since  1.6.3
	 * @access public
	 * @var    string
	 */
	public $type = 'go-pro';

	/**
	 * Custom button text to output.
	 *
	 * @since  1.6.3
	 * @access public
	 * @var    string
	 */
	public $pro_text = '';

	/**
	 * Custom pro button URL.
	 *
	 * @since  1.6.3
	 * @access public
	 * @var    string
	 */
	public $pro_url = '';

	/**
	 * Custom classes for the .button.
	 *
	 * @since  1.8.9
	 * @access public
	 * @var    string
	 */
	public $pro_classes = '';

	/**
	 * Add custom parameters to pass to the JS via JSON.
	 *
	 * @since  1.6.3
	 * @access public
	 * @return void
	 */
	public function json() {
		$json = parent::json();

		$json['pro_text'] = $this->pro_text;
		$json['pro_url']  = esc_url( $this->pro_url );
		$json['pro_classes']  = esc_html( $this->pro_classes );

		return $json;
	}

	/**
	 * Outputs the Underscore.js template.
	 *
	 * @since  1.6.3
	 * @access public
	 * @return void
	 */
	protected function render_template() { ?>

		<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand">

			<h3 class="accordion-section-title">
				{{ data.title }}

				<# if ( data.pro_text && data.pro_url ) { #>
					<a href="{{ data.pro_url }}" class="button button-secondary alignright {{ data.pro_classes }}" target="_blank">{{ data.pro_text }}</a>
				<# } #>
			</h3>
		</li>
	<?php }
}
