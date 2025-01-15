<?php
namespace HelloPlus\Modules\Content\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Control_Choose;

class Choose_Img_Control extends Control_Choose {
	public function get_type() {
		return 'choose-img';
	}

	public function content_template() {
		$control_uid_input_type = '{{value}}';
		?>
		<div class="elementor-control-field">
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<div class="elementor-choices elementor-choices-img">
					<# _.each( data.options, function( options, value ) { #>
					<input id="<?php $this->print_control_uid( $control_uid_input_type ); ?>" type="radio" name="elementor-choose-{{ data.name }}-{{ data._cid }}" value="{{ value }}">
					<label class="elementor-choices-label elementor-control-unit-2 tooltip-target" for="<?php $this->print_control_uid( $control_uid_input_type ); ?>" data-tooltip="{{ options.title }}" title="{{ options.title }}">
						<img src="{{ options.image }}" aria-hidden="true" alt="{{ options.title }}">
						<span class="elementor-screen-only">{{{ options.title }}}</span>
					</label>
					<# } ); #>
				</div>
			</div>
		</div>

		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}
