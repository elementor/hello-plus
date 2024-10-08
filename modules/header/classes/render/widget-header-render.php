<?php

namespace HelloPlus\Modules\Header\Classes\Render;

use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Utils;

use HelloPlus\Modules\Header\Base\Traits\Shared_Header_Traits;
use HelloPlus\Includes\Utils as Theme_Utils;
use HelloPlus\Modules\Header\Widgets\Header;

class Widget_Header_Render {
	protected Header $widget;
	use Shared_Header_Traits;

	const LAYOUT_CLASSNAME = 'ehp-header';
	const SITE_LINK_CLASSNAME = 'ehp-header__site-link';
	const CTAS_CONTAINER_CLASSNAME = 'ehp-header__ctas-container';
	const BUTTON_CLASSNAME = 'ehp-header__button';

	protected array $settings;

	public function __construct( Header $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}

	public function render(): void {
		$layout_classnames = self::LAYOUT_CLASSNAME;
		$custom_classes = $this->settings['advanced_custom_css_classes'] ?? '';

		if ( $custom_classes ) {
			$layout_classnames .= ' ' . $custom_classes;
		}

		$this->widget->add_render_attribute( 'layout', [
			'id' => $this->settings['advanced_custom_css_id'],
			'class' => $layout_classnames,
		] );
		?>
		<div <?php echo $this->widget->get_render_attribute_string( 'layout' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
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
		$site_title_text = $this->get_site_title();
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
		<a <?php echo $this->widget->get_render_attribute_string( 'site-link' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php if ( $site_logo_image ) { ?>
				<div class="ehp-header__site-logo">
					<?php Group_Control_Image_Size::print_attachment_image_html( $this->settings, 'site_logo_image' ); ?>
				</div>
			<?php } else {
				$site_title_output = sprintf( '<%1$s %2$s %3$s>%4$s</%1$s>', Utils::validate_html_tag( $site_title_tag ), $this->widget->get_render_attribute_string( 'heading' ), 'class="ehp-header__site-title"', esc_html( $site_title_text ) );
				// Escaped above
				Utils::print_unescaped_internal_string( $site_title_output );
			} ?>
		</a>
		<?php
	}

	public function render_navigation(): void {
		$available_menus = $this->get_available_menus();

		if ( ! $available_menus ) {
			return;
		}

		$settings = $this->settings;// $this->get_active_settings();
		$submenu_layout = $this->settings['style_submenu_layout'] ?? 'horizontal';

		$args = [
			'echo' => false,
			'menu' => $settings['navigation_menu'],
			'menu_class' => 'elementor-nav-menu',
			'menu_id' => '123', // 'menu-' . $this->get_nav_menu_index() . '-' . $this->get_id(),
			'fallback_cb' => '__return_empty_string',
			'container' => '',
		];

		if ( 'vertical' === $submenu_layout ) {
			$args['menu_class'] .= ' sm-vertical';
		}

		// General Menu.
		$menu_html = wp_nav_menu( $args );

		if ( empty( $menu_html ) ) {
			return;
		}

		if ( $settings['navigation_menu_name'] ) {
			$this->widget->add_render_attribute( 'main-menu', 'aria-label', $settings['navigation_menu_name'] );
		}

		if ( 'dropdown' !== $submenu_layout ) :
			$this->widget->add_render_attribute( 'main-menu', 'class', [
				'elementor-nav-menu--main',
				'elementor-nav-menu__container',
				'elementor-nav-menu--layout-' . $submenu_layout,
				'ehp-header__navigation',
			] );
			?>
			<nav <?php $this->widget->print_render_attribute_string( 'main-menu' ); ?>>
				<?php
					// PHPCS - escaped by WordPress with "wp_nav_menu"
					echo $menu_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</nav>
			<?php
		endif;
		$this->render_menu_toggle();
	}

	private function render_menu_toggle() {
		$toggle_icon = $this->settings['navigation_menu_icon'];

		$this->widget->add_render_attribute( 'button-toggle', [
			'class' => 'ehp-header__button-toggle',
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
						'class' => 'ehp-header__toggle-icon',
						'role' => 'presentation',
					]
				);
			?>
			<span class="elementor-screen-only"><?php echo esc_html__( 'Menu', 'hello-plus' ); ?></span>
		</button>
		<?php
	}

	public function render_ctas_container(): void {
		$primary_cta_button_text = $this->settings['primary_cta_button_text'];
		$secondary_cta_button_text = $this->settings['secondary_cta_button_text'];
		$has_primary_button = ! empty( $primary_cta_button_text );
		$has_secondary_button = ! empty( $secondary_cta_button_text );

		$ctas_container_classnames = self::CTAS_CONTAINER_CLASSNAME;

		$this->widget->add_render_attribute( 'ctas-container', [
			'class' => $ctas_container_classnames,
		] );
		?>
			<div <?php echo $this->widget->get_render_attribute_string( 'ctas-container' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
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
		<a <?php echo $this->widget->get_render_attribute_string( $type . '-button' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
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
		return [ 'url' => $this->get_site_url() ];
	}
}
