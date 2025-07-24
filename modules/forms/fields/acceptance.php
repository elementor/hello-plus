<?php
namespace HelloPlus\Modules\Forms\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Acceptance extends Field_Base {

	public function get_type() {
		return 'ehp-acceptance';
	}

	public function get_name() {
		return esc_html__( 'Acceptance', 'hello-plus' );
	}

	public function render( $item, $item_index, $form ) {
		$label = '';
		$form->add_render_attribute( 'input' . $item_index, 'class', 'elementor-acceptance-field' );
		$form->add_render_attribute( 'input' . $item_index, 'type', 'checkbox', true );

		if ( ! empty( $item['acceptance_text'] ) ) {
			$label = '<label for="' . $form->get_attribute_id( $item ) . '">' . $item['acceptance_text'] . '</label>';
		}

		if ( ! empty( $item['checked_by_default'] ) ) {
			$form->add_render_attribute( 'input' . $item_index, 'checked', 'checked' );
		}

		?>
		<div class="elementor-field-subgroup">
			<span class="elementor-field-option">
				<input <?php $form->print_render_attribute_string( 'input' . $item_index ); ?>>
				<?php
				echo wp_kses( $label, [
					'label' => [
						'for' => true,
						'class' => true,
						'id' => true,
						'style' => true,
					],
				] ); ?>
			</span>
		</div>
		<?php
	}
}
