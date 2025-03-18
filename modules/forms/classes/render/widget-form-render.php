<?php
namespace HelloPlus\Modules\Forms\Classes\Render;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Modules\Forms\Widgets\Ehp_Form;
use HelloPlus\Includes\Utils;
use Elementor\Icons_Manager;
use Elementor\Utils as Elementor_Utils;

use HelloPlus\Traits\Shared_Traits;

class Widget_Form_Render {
	protected Ehp_Form $widget;
	protected array $settings;

	const LAYOUT_CLASSNAME = 'ehp-form';

	use Shared_Traits;

	public function render() {
		$form_name = $this->settings['form_name'];

		if ( ! empty( $form_name ) ) {
			$this->widget->add_render_attribute( 'form', [
				'name' => $form_name,
				'class' => self::LAYOUT_CLASSNAME,
				'method' => 'post',
			] );
		}

		$this->widget->add_render_attribute( 'wrapper', [
			'class' => self::LAYOUT_CLASSNAME . '__wrapper',
		] );

		$referer_title = trim( wp_title( '', false ) );

		if ( ! $referer_title && is_home() ) {
			$referer_title = get_option( 'blogname' );
		}

		?>
		<form <?php $this->widget->print_render_attribute_string( 'form' ); ?>>
			<?php $this->render_text_container(); ?>
			<input type="hidden" name="post_id" value="<?php echo (int) Utils::get_current_post_id(); ?>"/>
			<input type="hidden" name="form_id" value="<?php echo esc_attr( $this->widget->get_id() ); ?>"/>
			<input type="hidden" name="referer_title" value="<?php echo esc_attr( $referer_title ); ?>"/>

			<?php if ( is_singular() ) {
				// `queried_id` may be different from `post_id` on Single theme builder templates.
				?>
				<input type="hidden" name="queried_id" value="<?php echo (int) get_the_ID(); ?>"/>
			<?php } ?>

			<div <?php $this->widget->print_render_attribute_string( 'wrapper' ); ?>>
				<?php
				foreach ( $this->settings['form_fields'] as $item_index => $item ) :
					$item['input_size'] = $this->settings['input_size'];
					$this->widget->form_fields_render_attributes( $item_index, $this->settings, $item );

					$field_type = $item['field_type'];

					/**
					 * Render form field.
					 *
					 * Filters the field rendered by Elementor forms.
					 *
					 * @param array $item The field value.
					 * @param int $item_index The field index.
					 * @param Ehp_Form $this An instance of the form.
					 *
					 * @since 1.0.0
					 *
					 */
					$item = apply_filters( 'hello_plus/forms/render/item', $item, $item_index, $this );

					/**
					 * Render form field.
					 *
					 * Filters the field rendered by Elementor forms.
					 *
					 * The dynamic portion of the hook name, `$field_type`, refers to the field type.
					 *
					 * @param array $item The field value.
					 * @param int $item_index The field index.
					 * @param Ehp_Form $this An instance of the form.
					 *
					 * @since 1.0.0
					 *
					 */
					$item = apply_filters( "hello_plus/forms/render/item/{$field_type}", $item, $item_index, $this );

					$print_label = ! in_array( $item['field_type'], [ 'hidden', 'html', 'step' ], true );
					?>
					<div <?php $this->widget->print_render_attribute_string( 'field-group' . $item_index ); ?>>
						<?php
						if ( $print_label && $item['field_label'] ) {
							?>
							<label <?php $this->widget->print_render_attribute_string( 'label' . $item_index ); ?>>
								<?php
								echo esc_html( $item['field_label'] ); ?>
							</label>
							<?php
						}

						switch ( $item['field_type'] ) :
							case 'textarea':
								echo wp_kses(
									$this->widget->make_textarea_field( $item, $item_index, $this->settings ),
									[
										'textarea' => [
											'cols' => true,
											'rows' => true,
											'name' => true,
											'id' => true,
											'class' => true,
											'style' => true,
											'placeholder' => true,
											'maxlength' => true,
											'required' => true,
											'readonly' => true,
											'disabled' => true,
										],
									]
								);
								break;

							case 'select':
								echo wp_kses( $this->widget->make_select_field( $item, $item_index ), [
									'select' => [
										'name' => true,
										'id' => true,
										'class' => true,
										'style' => true,
										'required' => true,
										'disabled' => true,
									],
									'option' => [
										'value' => true,
										'selected' => true,
									],
								] );
								break;

							case 'text':
							case 'email':
								$this->widget->add_render_attribute( 'input' . $item_index, 'class', 'elementor-field-textual' );
								?>
								<input size="1" <?php $this->widget->print_render_attribute_string( 'input' . $item_index ); ?>>
								<?php
								break;

							default:
								$field_type = $item['field_type'];

								/**
								 * Hello+ form field render.
								 *
								 * Fires when a field is rendered in the frontend. This hook allows developers to
								 * add functionality when from fields are rendered.
								 *
								 * The dynamic portion of the hook name, `$field_type`, refers to the field type.
								 *
								 * @param array $item The field value.
								 * @param int $item_index The field index.
								 * @param Ehp_Form $this An instance of the form.
								 *
								 * @since 1.0.0
								 *
								 */
								do_action( "hello_plus/forms/render_field/{$field_type}", $item, $item_index, $this->widget );
						endswitch;
						?>
					</div>
				<?php endforeach; ?>
				<?php $this->render_button(); ?>
			</div>
		</form>
		<?php
	}

	protected function render_button(): void {
		$button_classnames = [ self::LAYOUT_CLASSNAME . '__button' ];
		$submit_group_classnames = [ self::LAYOUT_CLASSNAME . '__submit-group' ];

		if ( ! empty( $this->settings['button_width'] ) ) {
			$submit_group_classnames[] = 'has-width-' . $this->settings['button_width'];

			if ( ! empty( $this->settings['button_width_tablet'] ) ) {
				$submit_group_classnames[] = 'has-width-md-' . $this->settings['button_width_tablet'];
			}

			if ( ! empty( $this->settings['button_width_mobile'] ) ) {
				$submit_group_classnames[] = 'has-width-sm-' . $this->settings['button_width_mobile'];
			}
		}

		$this->widget->add_render_attribute( 'submit-group', [
			'class' => $submit_group_classnames,
		] );

		if ( 'yes' === $this->settings['button_border_switcher'] ) {
			$button_classnames[] = 'has-border';
		}

		if ( ! empty( $this->settings['button_shape'] ) ) {
			$button_classnames[] = 'has-shape-' . $this->settings['button_shape'];
		}

		if ( ! empty( $this->settings['button_type'] ) ) {
			$button_classnames[] = 'is-type-' . $this->settings['button_type'];
		}

		$this->widget->add_render_attribute( 'button', [
			'class' => $button_classnames,
			'type' => 'submit',
		] );

		if ( $this->settings['button_hover_animation'] ) {
			$this->widget->add_render_attribute( 'button', 'class', 'elementor-animation-' . $this->settings['button_hover_animation'] );
		}

		if ( ! empty( $this->settings['button_css_id'] ) ) {
			$this->widget->add_render_attribute( 'button', 'id', $this->settings['button_css_id'] );
		}

		$this->widget->add_render_attribute( 'button-text', [
			'class' => self::LAYOUT_CLASSNAME . '__button-text',
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'submit-group' ); ?>>
			<button <?php $this->widget->print_render_attribute_string( 'button' ); ?>>
				<?php if ( ! empty( $this->settings['selected_button_icon'] ) || ! empty( $this->settings['selected_button_icon']['value'] ) ) : ?>
					<?php
					Icons_Manager::render_icon( $this->settings['selected_button_icon'],
						[
							'aria-hidden' => 'true',
							'class' => self::LAYOUT_CLASSNAME . '__button-icon',
						],
					);
					?>
				<?php endif; ?>

				<?php if ( ! empty( $this->settings['button_text'] ) ) : ?>
					<span <?php $this->widget->print_render_attribute_string( 'button-text' ); ?>><?php $this->widget->print_unescaped_setting( 'button_text' ); ?></span>
				<?php endif; ?>
			</button>
		</div>
		<?php
	}

	protected function render_text_container(): void {
		$heading_classname = self::LAYOUT_CLASSNAME . '__heading';
		$description_classname = self::LAYOUT_CLASSNAME . '__description';

		$this->widget->add_render_attribute( 'text-container', [
			'class' => self::LAYOUT_CLASSNAME . '__text-container',
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'text-container' ); ?>>
			<?php
				$this->maybe_render_text_html( 'text_heading', $heading_classname, $this->settings['text_heading'], $this->settings['text_heading_tag'] );
				$this->maybe_render_text_html( 'text_description', $description_classname, $this->settings['text_description'] );
			?>
		</div>
		<?php
	}

	public function __construct( Ehp_Form $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}
}
