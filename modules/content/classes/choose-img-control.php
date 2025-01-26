<?php
namespace HelloPlus\Modules\Content\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Control_Choose;

class Choose_Img_Control extends Control_Choose {

	const CONTROL_NAME = 'choose-img';

	public function get_type() {
		return self::CONTROL_NAME;
	}

	public function content_template() {
		$control_uid_input_type = '{{value}}';
		?>
		<div class="elementor-control-field">
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<div class="elementor-choices elementor-choices-img">
					<#
						const columns = [];
						const maxColumns = data.columns;
						let currentCol = 0;
						_.each( data.options, function( options, value ) {
							if ( ! Array.isArray( columns[currentCol] ) ) {
								columns[currentCol] = [];
							}
							columns[currentCol].push( { options, value } );
							currentCol += 1;
							if ( currentCol === maxColumns ) {
								currentCol = 0;
							}
						} );

					#>
					<# columns.forEach( column => { #>
						<div class="elementor-choices-column">
							<# column.forEach( content => { #>
								<# const { options, value } = content; #>
								<input id="<?php $this->print_control_uid( $control_uid_input_type ); ?>" type="radio" name="elementor-choose-{{ data.name }}-{{ data._cid }}" value="{{ value }}">
								<label class="elementor-choices-label elementor-control-unit-2 tooltip-target" for="<?php $this->print_control_uid( $control_uid_input_type ); ?>" data-tooltip="{{ options.title }}" title="{{ options.title }}">
									<img class="elementor-choices-image" src="{{ options.image }}" aria-hidden="true" alt="{{ options.title }}" data-hover="{{ value }}" />
									<#
										const showHoverImage = typeof options.hover_image === 'boolean' || typeof options.hover_image === 'string';
										const isSameImageOnHover = typeof options.hover_image === 'boolean';
										const hoverImageSrc = isSameImageOnHover ? options.image : options.hover_image;
										const extraClass = isSameImageOnHover ? 'elementor-choices-img-zoom' : '';
									#>
									<# if ( showHoverImage ) { #>
										<img class="elementor-choices-image-hover {{ extraClass }}" src="{{ hoverImageSrc }}" data-show-hover="{{ value }}" />
									<# } #>
									<span class="elementor-screen-only">{{{ options.title }}}</span>
								</label>
							<# } ); #>
						</div>
					<# } ); #>
					<# _.each( data.options, function( options, value ) { #>

					<# } ); #>
				</div>
			</div>
		</div>

		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}

	protected function get_default_settings() {
		return [
			'options' => [],
			'toggle' => true,
			'columns' => 1,
		];
	}
}
