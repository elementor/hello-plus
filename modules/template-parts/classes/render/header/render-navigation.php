<?php

namespace HelloPlus\Modules\TemplateParts\Classes\Render\Header;

use HelloPlus\Classes\Ehp_Shapes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Icons_Manager;
use HelloPlus\Classes\Render_Base;
use HelloPlus\Classes\Widget_Utils;

class Render_Navigation extends Render_Base {

	private int $nav_menu_index = 0;
	private function get_and_advance_nav_menu_index(): int {
		return $this->nav_menu_index++;
	}

	public function render(): void {
		$available_menus = Widget_Utils::get_available_menus();

		$menu_classname = $this->get_class_name( '__menu' );

		$pointer_hover_type = $this->settings['style_navigation_pointer_hover'] ?? '';
		$focus_active_type = $this->settings['style_navigation_focus_active'] ?? '';
		$has_responsive_divider = $this->settings['style_responsive_menu_divider'];

		if ( 'none' !== $pointer_hover_type ) {
			$menu_classname .= ' has-pointer-hover-' . $pointer_hover_type;
		}

		if ( 'none' !== $focus_active_type ) {
			$menu_classname .= ' has-focus-active-' . $focus_active_type;
		}

		if ( 'yes' === $has_responsive_divider ) {
			$menu_classname .= ' has-responsive-divider';
		}

		$settings = $this->settings;
		$submenu_layout = $this->settings['style_submenu_layout'] ?? 'horizontal';

		$args = [
			'echo' => false,
			'menu' => $settings['navigation_menu'],
			'menu_class' => $menu_classname,
			'menu_id' => 'menu-' . $this->get_and_advance_nav_menu_index() . '-' . $this->widget->get_id(),
			'fallback_cb' => '__return_empty_string',
			'container' => '',
		];

		$menu_name = $settings['navigation_menu_name'] ?? __( 'Navigation', 'hello-plus' );

		if ( $menu_name ) {
			$this->widget->add_render_attribute( 'main-menu', 'aria-label', $menu_name );
		}

		$this->widget->add_render_attribute( 'main-menu', 'class', [
			' has-submenu-layout-' . $submenu_layout,
			$this->get_class_name( '__navigation' ),
		] );
		?>

		<nav <?php $this->widget->print_render_attribute_string( 'main-menu' ); ?>>
			<?php
			// Add custom filter to handle Nav Menu HTML output.
			add_filter( 'nav_menu_link_attributes', [ $this, 'handle_link_classes' ], 10, 4 );
			add_filter( 'nav_menu_submenu_css_class', [ $this, 'handle_sub_menu_classes' ] );
			add_filter( 'walker_nav_menu_start_el', [ $this, 'handle_walker_menu_start_el' ], 10, 4 );
			add_filter( 'nav_menu_item_id', '__return_empty_string' );

			$args['echo'] = true;

			if ( $available_menus ) {
				wp_nav_menu( $args );
			}

			// Remove all our custom filters.
			remove_filter( 'nav_menu_link_attributes', [ $this, 'handle_link_classes' ] );
			remove_filter( 'nav_menu_submenu_css_class', [ $this, 'handle_sub_menu_classes' ] );
			remove_filter( 'walker_nav_menu_start_el', [ $this, 'handle_walker_menu_start_el' ] );
			remove_filter( 'nav_menu_item_id', '__return_empty_string' );

			$this->render_ctas_container();
			?>
		</nav>
		<?php
	}

	protected function render_ctas_container() {
		$render_ctas_container = new Render_Ctas_Container( $this->widget, $this->layout_classname );
		$render_ctas_container->render();
	}

	public function render_menu_toggle() {
		$show_contact_buttons = 'yes' === $this->settings['contact_buttons_show'] || 'yes' === $this->settings['contact_buttons_show_connect'];
		$has_menu_cart = $this->settings['menu_cart_icon_show'] ?? '';

		$this->widget->add_render_attribute( 'side-toggle', 'class', $this->get_class_name( '__side-toggle' ) );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'side-toggle' ); ?>>
			<?php
			if ( $show_contact_buttons ) {
				$this->render_contact_buttons();
			}

			if ( empty( $has_menu_cart ) || 'no' === $has_menu_cart ) {
				$this->render_button_toggle();
			}

			if ( 'yes' === $has_menu_cart ) {
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

	public function render_button_toggle() {
		$toggle_icon = $this->settings['navigation_menu_icon'] ?? [
			'value' => 'fas fa-bars',
			'library' => 'fa-solid',
		];

		$toggle_classname = $this->get_class_name( '__button-toggle' );

		$this->widget->add_render_attribute( 'button-toggle', [
			'class' => $toggle_classname,
			'role' => 'button',
			'tabindex' => '0',
			'aria-label' => esc_html__( 'Menu Toggle', 'hello-plus' ),
			'aria-expanded' => 'false',
		] );

		$this->widget->add_render_attribute( 'toggle-icon-open', [
			'class' => [
				$this->get_class_name( '__toggle-icon' ),
				$this->get_class_name( '__toggle-icon--open' ),
			],
			'aria-hidden' => 'true',
		] );

		$this->widget->add_render_attribute( 'toggle-icon-close', [
			'class' => [
				$this->get_class_name( '__toggle-icon' ),
				$this->get_class_name( '__toggle-icon--close' ),
			],
			'aria-hidden' => 'true',
		] );
		?>
		<button <?php $this->widget->print_render_attribute_string( 'button-toggle' ); ?>>
			<span <?php $this->widget->print_render_attribute_string( 'toggle-icon-open' ); ?>>
				<?php
				Icons_Manager::render_icon( $toggle_icon,
					[
						'role' => 'presentation',
					]
				);
				?>
			</span>
			<span <?php $this->widget->print_render_attribute_string( 'toggle-icon-close' ); ?>>
				<?php
				Icons_Manager::render_icon(
					[
						'library' => 'eicons',
						'value' => 'eicon-close',
					]
				);
				?>
			</span>
			<span class="elementor-screen-only"><?php esc_html_e( 'Menu', 'hello-plus' ); ?></span>
		</button>

		<?php
	}

	public function handle_link_classes( $atts, $item, $args, $depth ) {
		$classes = [
			$this->get_class_name( '__item' ),
			$depth ? $this->get_class_name( '__item--sub-level' ) : $this->get_class_name( '__item--top-level' ),
		];

		$is_anchor = false !== strpos( $atts['href'], '#' );

		if ( ! $is_anchor && in_array( 'current-menu-item', $item->classes, true ) ) {
			$classes[] = 'is-item-active';
		}

		if ( $is_anchor ) {
			$classes[] = 'is-item-anchor';
		}

		$class_string = implode( ' ', $classes );

		if ( empty( $atts['class'] ) ) {
			$atts['class'] = $class_string;
		} else {
			$atts['class'] .= ' ' . $class_string;
		}

		return $atts;
	}

	public function handle_sub_menu_classes() {
		$submenu_layout = $this->settings['style_submenu_layout'] ?? 'horizontal';

		$dropdown_classnames = [ $this->get_class_name( '__dropdown' ) ];
		$dropdown_classnames[] = 'has-layout-' . $submenu_layout;

		$shapes = new Ehp_Shapes( $this->widget, [
			'container_prefix' => 'submenu',
			'control_prefix' => 'style',
			'widget_name' => 'header',
			'is_responsive' => false,
		] );
		$classnames = array_merge( $dropdown_classnames, $shapes->get_shape_classnames() );

		return $classnames;
	}

	public function handle_walker_menu_start_el( $item_output, $item ) {

		if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
			$submenu_icon = $this->settings['navigation_menu_submenu_icon'];

			$svg_icon = Icons_Manager::try_get_icon_html( $submenu_icon,
				[
					'aria-hidden' => 'true',
					'class' => $this->get_class_name( '__submenu-toggle-icon' ),
				]
			);

			$button_classes = $this->get_class_name( '__item' ) . ' ' . $this->get_class_name( '__dropdown-toggle' );

			$item_output = '<button type="button" class="' . $button_classes . '" aria-expanded="false">' . esc_html( $item->title ) . $svg_icon . '</button>';
		}

		return $item_output;
	}
}
