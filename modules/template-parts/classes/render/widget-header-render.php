<?php

namespace HelloPlus\Modules\TemplateParts\Classes\Render;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\{
	Group_Control_Image_Size,
	Icons_Manager,
	Utils
};

use HelloPlus\Modules\TemplateParts\Widgets\Ehp_Header;
use HelloPlus\Modules\TemplateParts\Classes\Render\Header\Render_Navigation;
use HelloPlus\Modules\TemplateParts\Classes\Render\Header\Render_Ctas_Container;
use HelloPlus\Modules\TemplateParts\Classes\Render\Header\Render_Menu_Cart;
use HelloPlus\Modules\TemplateParts\Classes\Render\Header\Render_Contact_Buttons;

use HelloPlus\Classes\{
	Ehp_Button,
	Ehp_Shapes,
	Ehp_Social_Platforms,
	Widget_Utils,
};

/**
 * class Widget_Header_Render
 */
class Widget_Header_Render {

	const LAYOUT_CLASSNAME = 'ehp-header';

	protected Ehp_Header $widget;

	protected array $settings;

	protected int $nav_menu_index = 1;

	protected function should_show_button_toggle(): bool {
		$show_contact_buttons = 'yes' === $this->settings['contact_buttons_show'] || 'yes' === $this->settings['contact_buttons_show_connect'];

		if ( $show_contact_buttons ) {
			$show_contact_buttons = 'dropdown' === $this->settings['contact_buttons_responsive_display'];
		}

		$has_at_least_one_button = $show_contact_buttons ||
			! empty( $this->settings['primary_cta_button_text'] ) ||
			! empty( $this->settings['secondary_cta_button_text'] );

		if ( $has_at_least_one_button ) {
			return true;
		}

		$available_menus = $this->widget->get_available_menus();
		if ( ! $available_menus ) {
			return false;
		}

		$empty_menus = $this->widget->get_empty_menus();
		$navigation_menu = $this->settings['navigation_menu'] ?? '';
		$has_empty_menu = $navigation_menu && in_array( $navigation_menu, $empty_menus, true );

		if ( $has_empty_menu ) {
			return false;
		}

		return true;
	}

	public function render(): void {
		$layout_classnames = [
			self::LAYOUT_CLASSNAME,
		];
		$navigation_breakpoint = $this->settings['navigation_breakpoint'] ?? 'mobile-portrait';
		$box_border = $this->settings['show_box_border'] ?? '';
		$behavior_float = $this->settings['behavior_float'];
		$behavior_on_scroll = $this->settings['behavior_onscroll_select'];
		$layout_preset = $this->settings['layout_preset_select'];
		$behavior_scale_logo = $this->settings['behavior_sticky_scale_logo'];
		$behavior_scale_title = $this->settings['behavior_sticky_scale_title'];
		$has_blur_background = $this->settings['blur_background'];

		if ( ! empty( $navigation_breakpoint ) ) {
			$this->widget->add_render_attribute( 'layout', [
				'data-responsive-breakpoint' => $navigation_breakpoint,
			] );
		}

		if ( 'yes' === $box_border ) {
			$layout_classnames[] = 'has-box-border';
		}

		if ( 'yes' === $behavior_float ) {
			$layout_classnames[] = 'has-behavior-float';
		}

		if ( 'yes' === $behavior_scale_logo ) {
			$layout_classnames[] = 'has-behavior-sticky-scale-logo';
		}

		if ( 'yes' === $behavior_scale_title ) {
			$layout_classnames[] = 'has-behavior-sticky-scale-title';
		}

		$shapes = new Ehp_Shapes( $this->widget, [
			'container_prefix' => 'float',
			'control_prefix' => 'behavior',
			'render_attribute' => 'layout',
			'widget_name' => 'header',
		] );

		$shapes->add_shape_attributes();

		if ( ! empty( $behavior_on_scroll ) ) {
			$layout_classnames[] = 'has-behavior-onscroll-' . $behavior_on_scroll;
		}

		if ( 'navigate' === $layout_preset ) {
			$layout_classnames[] = 'has-align-link-start';
		} elseif ( 'identity' === $layout_preset ) {
			$layout_classnames[] = 'has-align-link-center';
		} elseif ( 'connect' === $layout_preset ) {
			$layout_classnames[] = 'has-align-link-connect';
		}

		if ( 'yes' === $has_blur_background ) {
			$layout_classnames[] = 'has-blur-background';
		}

		$render_attributes = [
			'class' => $layout_classnames,
			'data-scroll-behavior' => $behavior_on_scroll,
			'data-behavior-float' => $behavior_float,
		];

		$this->widget->add_render_attribute( 'layout', $render_attributes );

		$this->widget->maybe_add_advanced_attributes();

		$this->widget->add_render_attribute( 'elements-container', 'class', self::LAYOUT_CLASSNAME . '__elements-container' );

		$this->widget->add_render_attribute( 'menu-cart-container', 'class', self::LAYOUT_CLASSNAME . '__menu-cart-container' );
		?>
		<header <?php $this->widget->print_render_attribute_string( 'layout' ); ?>>
			<div <?php $this->widget->print_render_attribute_string( 'elements-container' ); ?>>
				<?php
				$render_navigation = new Render_Navigation( $this->widget, self::LAYOUT_CLASSNAME );

				if ( $this->should_show_button_toggle() ) {
					?>
					<div <?php $this->widget->print_render_attribute_string( 'menu-cart-container' ); ?>>
						<?php $render_navigation->render_button_toggle(); ?>
					</div>
					<?php
				}

				$this->widget->render_site_link( 'header' );
				$render_navigation->render();
				$this->render_ctas_container();
				?>
			</div>
		</header>
		<?php
	}

	protected function render_ctas_container() {
		$render_ctas_container = new Render_Ctas_Container( $this->widget, self::LAYOUT_CLASSNAME );
		$render_ctas_container->render();
	}


	public function __construct( Ehp_Header $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}
}
