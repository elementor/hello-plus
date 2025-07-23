<?php

namespace HelloPlus\Modules\TemplateParts\Classes\Render\Header;

use HelloPlus\Classes\Ehp_Button;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Icons_Manager;
use HelloPlus\Classes\Render_Base;

class Render_Ctas_Container extends Render_Base {

	public function render(): void {
		$responsive_button_width = $this->settings['cta_responsive_width'] ?? '';
		$ctas_container_classnames = $this->get_class_name( '__ctas-container' );
		$show_contact_buttons = 'yes' === $this->settings['contact_buttons_show'] || 'yes' === $this->settings['contact_buttons_show_connect'];

		if ( '' !== $responsive_button_width ) {
			$ctas_container_classnames .= ' has-responsive-width-' . $responsive_button_width;
		}

		$this->widget->add_render_attribute( 'ctas-container', [
			'class' => $ctas_container_classnames,
		] );

		?>
		<div <?php $this->widget->print_render_attribute_string( 'ctas-container' ); ?>>
			<?php
			if ( $show_contact_buttons ) {
				$this->render_contact_buttons();
			}

			if ( ! empty( $this->settings['secondary_cta_button_text'] ) ) {
				$this->render_button( 'secondary' );
			}

			if ( ! empty( $this->settings['primary_cta_button_text'] ) ) {
				$this->render_button( 'primary' );
			}

			if ( isset( $this->settings['menu_cart_icon_show'] ) && 'yes' === $this->settings['menu_cart_icon_show'] ) {
				$this->render_menu_cart();
			}
			?>
		</div>
		<?php
	}

	protected function render_menu_cart() {
		$render_menu_cart = new Render_Menu_Cart( $this->widget, $this->layout_classname );
		$render_menu_cart->render();
	}

	protected function render_contact_buttons() {
		$render_contact_buttons = new Render_Contact_Buttons( $this->widget, $this->layout_classname );
		$render_contact_buttons->render();
	}

	protected function render_button( $type ) {
		$button = new Ehp_Button( $this->widget, [
			'type' => $type,
			'widget_name' => 'header',
		] );
		$button->render();
	}
}
