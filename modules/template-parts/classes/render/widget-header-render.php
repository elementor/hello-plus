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

use HelloPlus\Modules\TemplateParts\Widgets\Header;

/**
 * class Widget_Header_Render
 */
class Widget_Header_Render {
	const LAYOUT_CLASSNAME = 'ehp-header';
	const SITE_LINK_CLASSNAME = 'ehp-header__site-link';
	const CTAS_CONTAINER_CLASSNAME = 'ehp-header__ctas-container';
	const BUTTON_CLASSNAME = 'ehp-header__button';

	protected Header $widget;

	protected array $settings;

	public function render(): void {
		$layout_classnames = self::LAYOUT_CLASSNAME;
		$navigation_breakpoint = $this->settings['navigation_breakpoint'] ?? '';
		$box_border = $this->settings['show_box_border'] ?? '';
		$behavior_float = $this->settings['behavior_float'];
		$behavior_float_shape = $this->settings['behavior_float_shape'];
		$behavior_on_scroll = $this->settings['behavior_onscroll_select'];

		if ( ! empty( $navigation_breakpoint ) ) {
			$layout_classnames .= ' has-navigation-breakpoint-' . $navigation_breakpoint;
		}

		if ( 'yes' === $box_border ) {
			$layout_classnames .= ' has-box-border';
		}

		if ( 'yes' === $behavior_float ) {
			$layout_classnames .= ' has-behavior-float';
		}

		if ( ! empty( $behavior_float_shape ) ) {
			$layout_classnames .= ' has-shape-' . $behavior_float_shape;
		}

		if ( ! empty( $behavior_on_scroll ) ) {
			$layout_classnames .= ' has-behavior-onscroll-' . $behavior_on_scroll;
		}

		$this->widget->add_render_attribute( 'layout', [
			'class' => $layout_classnames,
			'data-scroll-behavior' => $behavior_on_scroll,
			'data-behavior-float' => $behavior_float,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'layout' ); ?>>
			<div class="ehp-header__elements-container">
				<?php
					$this->render_site_link();
					$this->render_navigation();
					$this->render_ctas_container();
				?>
			</div>
		</div>
		<?php
	}

	public function render_site_link(): void {
		$site_logo_image = $this->settings['site_logo_image'];
		$site_title_text = $this->widget->get_site_title();
		$site_title_tag = $this->settings['site_logo_title_tag'] ?? 'h2';
		$site_link_classnames = self::SITE_LINK_CLASSNAME;

		$this->widget->add_render_attribute( 'site-link', [
			'class' => $site_link_classnames,
		] );

		$site_link = $this->get_link_url();

		if ( $site_link ) {
			$this->widget->add_link_attributes( 'site-link', $site_link );
		}
		?>
		<a <?php $this->widget->print_render_attribute_string( 'site-link' ); ?>>
			<?php if ( $site_logo_image ) { ?>
				<?php Group_Control_Image_Size::print_attachment_image_html( $this->settings, 'site_logo_image' ); ?>
			<?php } else {
				$site_title_output = sprintf( '<%1$s %2$s %3$s>%4$s</%1$s>', Utils::validate_html_tag( $site_title_tag ), $this->widget->get_render_attribute_string( 'heading' ), 'class="ehp-header__site-title"', esc_html( $site_title_text ) );
				// Escaped above
				Utils::print_unescaped_internal_string( $site_title_output );
			} ?>
		</a>
		<?php
	}

	public function render_navigation(): void {
		$available_menus = $this->widget->get_available_menus();
		$menu_classname = 'ehp-header__menu';

		if ( ! $available_menus ) {
			return;
		}

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
			'menu_id' => 'menu-' . $this->widget->get_and_advance_nav_menu_index() . '-' . $this->widget->get_id(),
			'fallback_cb' => '__return_empty_string',
			'container' => '',
		];

		// Add custom filter to handle Nav Menu HTML output.
		add_filter( 'nav_menu_link_attributes', [ $this, 'handle_link_classes' ], 10, 4 );
		add_filter( 'nav_menu_submenu_css_class', [ $this, 'handle_sub_menu_classes' ] );
		add_filter( 'walker_nav_menu_start_el', [ $this, 'handle_walker_menu_start_el' ], 10, 4 );
		add_filter( 'nav_menu_item_id', '__return_empty_string' );

		// General Menu.
		$menu_html = wp_nav_menu( $args );

		// Remove all our custom filters.
		remove_filter( 'nav_menu_link_attributes', [ $this, 'handle_link_classes' ] );
		remove_filter( 'nav_menu_submenu_css_class', [ $this, 'handle_sub_menu_classes' ] );
		remove_filter( 'walker_nav_menu_start_el', [ $this, 'handle_walker_menu_start_el' ] );
		remove_filter( 'nav_menu_item_id', '__return_empty_string' );

		if ( empty( $menu_html ) ) {
			return;
		}

		if ( $settings['navigation_menu_name'] ) {
			$this->widget->add_render_attribute( 'main-menu', 'aria-label', $settings['navigation_menu_name'] );
		}

		$this->widget->add_render_attribute( 'main-menu', 'class', [
			' has-submenu-layout-' . $submenu_layout,
			'ehp-header__navigation',
		] );
		?>
		<span class="ehp-header__ghost-element"></span>

		<nav <?php $this->widget->print_render_attribute_string( 'main-menu' ); ?>>
			<?php
				// PHPCS - escaped by WordPress with "wp_nav_menu"
				echo $menu_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$this->render_ctas_container();
			?>
		</nav>
		<?php
		$this->render_menu_toggle();
	}

	private function render_menu_toggle() {
		$toggle_icon = $this->settings['navigation_menu_icon'];
		$toggle_classname = 'ehp-header__button-toggle';

		$this->widget->add_render_attribute( 'button-toggle', [
			'class' => $toggle_classname,
			'role' => 'button',
			'tabindex' => '0',
			'aria-label' => esc_html__( 'Menu Toggle', 'hello-plus' ),
			'aria-expanded' => 'false',
		] );

		?>
		<button <?php $this->widget->print_render_attribute_string( 'button-toggle' ); ?>>
			<?php
				Icons_Manager::render_icon( $toggle_icon,
					[
						'aria-hidden' => 'true',
						'class' => 'ehp-header__toggle-icon ehp-header__toggle-icon--open',
						'role' => 'presentation',
					]
				);
			?>
			<i class="eicon-close ehp-header__toggle-icon ehp-header__toggle-icon--close"></i>
			<span class="elementor-screen-only"><?php esc_html_e( 'Menu', 'hello-plus' ); ?></span>
		</button>
		<?php
	}

	protected function render_ctas_container() {
		$primary_cta_button_text = $this->settings['primary_cta_button_text'];
		$secondary_cta_button_text = $this->settings['secondary_cta_button_text'];
		$has_primary_button = ! empty( $primary_cta_button_text );
		$has_secondary_button = ! empty( $secondary_cta_button_text );

		$ctas_container_classnames = self::CTAS_CONTAINER_CLASSNAME;

		$this->widget->add_render_attribute( 'ctas-container', [
			'class' => $ctas_container_classnames,
		] );
		?>
			<div <?php $this->widget->print_render_attribute_string( 'ctas-container' ); ?>>
			<?php if ( $has_primary_button ) {
				$this->render_button( 'primary' );
			} ?>
			<?php if ( $has_secondary_button ) {
				$this->render_button( 'secondary' );
			} ?>
			</div>
		<?php
	}

	protected function render_button( $type ) {
		$button_text = $this->settings[ $type . '_cta_button_text' ];
		$button_link = $this->settings[ $type . '_cta_button_link' ];
		$button_icon = $this->settings[ $type . '_cta_button_icon' ];
		$button_hover_animation = $this->settings[ $type . '_button_hover_animation' ] ?? '';
		$button_has_border = $this->settings[ $type . '_show_button_border' ];
		$button_corner_shape = $this->settings[ $type . '_button_shape' ] ?? '';
		$button_type = $this->settings[ $type . '_button_type' ] ?? '';
		$button_classnames = self::BUTTON_CLASSNAME;

		$button_classnames .= ' ehp-header__button--' . $type;

		if ( ! empty( $button_type ) ) {
			$button_classnames .= ' is-type-' . $button_type;
		}

		if ( $button_hover_animation ) {
			$button_classnames .= ' elementor-animation-' . $button_hover_animation;
		}

		if ( 'yes' === $button_has_border ) {
			$button_classnames .= ' has-border';
		}

		if ( ! empty( $button_corner_shape ) ) {
			$button_classnames .= ' has-shape-' . $button_corner_shape;
		}

		$this->widget->add_render_attribute(  $type . '-button', [
			'class' => $button_classnames,
		] );

		if ( ! empty( $button_link ) ) {
			$this->widget->add_link_attributes( $type . '-button', $button_link );
		}

		?>
		<a <?php $this->widget->print_render_attribute_string( $type . '-button' ); ?>>
			<?php
				Icons_Manager::render_icon( $button_icon,
					[
						'aria-hidden' => 'true',
						'class' => 'ehp-header__button-icon',
					]
				);
			?>
			<?php echo esc_html( $button_text ); ?>
		</a>
		<?php
	}

	public function get_link_url(): array {
		return [ 'url' => $this->widget->get_site_url() ];
	}

	public function handle_link_classes( $atts, $item, $args, $depth ) {
		$classes = $depth ? 'ehp-header__item ehp-header__item--sub-level' : 'ehp-header__item ehp-header__item--top-level';
		$is_anchor = false !== strpos( $atts['href'], '#' );

		if ( ! $is_anchor && in_array( 'current-menu-item', $item->classes, true ) ) {
			$classes .= ' is-item-active';
		}

		if ( $is_anchor ) {
			$classes .= ' is-item-anchor';
		}

		if ( empty( $atts['class'] ) ) {
			$atts['class'] = $classes;
		} else {
			$atts['class'] .= ' ' . $classes;
		}

		return $atts;
	}

	public function handle_sub_menu_classes( $classes ) {
		$submenu_layout = $this->settings['style_submenu_layout'] ?? 'horizontal';
		$submenu_shape = $this->settings['style_submenu_shape'] ?? 'default';

		$dropdown_classnames = 'ehp-header__dropdown';
		$dropdown_classnames .= ' has-layout-' . $submenu_layout;
		$dropdown_classnames .= ' has-shape-' . $submenu_shape;

		$classes[] = $dropdown_classnames;

		return $classes;
	}

	public function handle_walker_menu_start_el( $item_output, $item ) {

		if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
			$submenu_icon = $this->settings['navigation_menu_submenu_icon'];

			$svg_icon = Icons_Manager::try_get_icon_html( $submenu_icon,
				[
					'aria-hidden' => 'true',
					'class' => 'ehp-header__submenu-toggle-icon',
				]
			);

			$item_output = '<button type="button" class="ehp-header__item ehp-header__dropdown-toggle" aria-expanded="false">' . esc_html( $item->title ) . $svg_icon . '</button>';
		}

		return $item_output;
	}

	public function __construct( Header $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}
}
