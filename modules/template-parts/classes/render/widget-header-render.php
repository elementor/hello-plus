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

	public function render(): void {
		$layout_classnames = [
			self::LAYOUT_CLASSNAME,
		];
		$navigation_breakpoint = $this->settings['navigation_breakpoint'] ?? '';
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
		?>
		<header <?php $this->widget->print_render_attribute_string( 'layout' ); ?>>
			<div <?php $this->widget->print_render_attribute_string( 'elements-container' ); ?>>
				<?php

				$this->widget->render_site_link( 'header' );
				$this->render_navigation();
				$this->render_ctas_container();
				?>
			</div>
		</header>
		<?php
	}

	public function render_navigation(): void {
		$available_menus = $this->widget->get_available_menus();

		$menu_classname = self::LAYOUT_CLASSNAME . '__menu';

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
			'menu_id' => 'menu-' . $this->get_and_advance_nav_menu_index() . '-' . $this->widget->get_id(),
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
			self::LAYOUT_CLASSNAME . '__navigation',
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

			wp_nav_menu( $args );

			// Remove all our custom filters.
			remove_filter( 'nav_menu_link_attributes', [ $this, 'handle_link_classes' ] );
			remove_filter( 'nav_menu_submenu_css_class', [ $this, 'handle_sub_menu_classes' ] );
			remove_filter( 'walker_nav_menu_start_el', [ $this, 'handle_walker_menu_start_el' ] );
			remove_filter( 'nav_menu_item_id', '__return_empty_string' );

			$this->render_ctas_container();
			?>
		</nav>
		<?php
		$this->render_menu_toggle();
	}

	private function render_menu_toggle() {
		$toggle_icon = $this->settings['navigation_menu_icon'];
		$toggle_classname = self::LAYOUT_CLASSNAME . '__button-toggle';
		$show_contact_buttons = 'yes' === $this->settings['contact_buttons_show'] || 'yes' === $this->settings['contact_buttons_show_connect'];

		$this->widget->add_render_attribute( 'button-toggle', [
			'class' => $toggle_classname,
			'role' => 'button',
			'tabindex' => '0',
			'aria-label' => esc_html__( 'Menu Toggle', 'hello-plus' ),
			'aria-expanded' => 'false',
		] );

		$this->widget->add_render_attribute( 'side-toggle', 'class', self::LAYOUT_CLASSNAME . '__side-toggle' );
		$this->widget->add_render_attribute( 'toggle-icon-open', [
			'class' => [
				self::LAYOUT_CLASSNAME . '__toggle-icon',
				self::LAYOUT_CLASSNAME . '__toggle-icon--open',
			],
			'aria-hidden' => 'true',
		] );

		$this->widget->add_render_attribute( 'toggle-icon-close', [
			'class' => [
				'eicon-close',
				self::LAYOUT_CLASSNAME . '__toggle-icon',
				self::LAYOUT_CLASSNAME . '__toggle-icon--close',
			],
		] );

		?>
		<div <?php $this->widget->print_render_attribute_string( 'side-toggle' ); ?>>
			<?php if ( $show_contact_buttons ) {
				$this->render_contact_buttons();
			} ?>
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
				<i <?php $this->widget->print_render_attribute_string( 'toggle-icon-close' ); ?>></i>
				<span class="elementor-screen-only"><?php esc_html_e( 'Menu', 'hello-plus' ); ?></span>
			</button>
		</div>
		<?php
	}

	protected function render_ctas_container() {
		$responsive_button_width = $this->settings['cta_responsive_width'] ?? '';
		$ctas_container_classnames = self::LAYOUT_CLASSNAME . '__ctas-container';
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
			?>
			<?php if ( ! empty( $this->settings['secondary_cta_button_text'] ) ) {
				$this->render_button( 'secondary' );
			} ?>
			<?php if ( ! empty( $this->settings['primary_cta_button_text'] ) ) {
				$this->render_button( 'primary' );
			} ?>
		</div>
		<?php
	}

	protected function render_contact_buttons() {
		$contact_buttons = $this->settings['contact_buttons_repeater'];
		$link_type = $this->settings['contact_buttons_link_type'];
		$responsive_display = $this->settings['contact_buttons_responsive_display'];
		$hover_animation = $this->settings['contact_button_hover_animation'];

		$contact_buttons_classnames = [
			self::LAYOUT_CLASSNAME . '__contact-buttons',
			'has-responsive-display-' . $responsive_display,
		];

		$this->widget->add_render_attribute( 'contact-buttons', [
			'class' => $contact_buttons_classnames,
		] );

		$ehp_platforms = new Ehp_Social_Platforms( $this->widget );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'contact-buttons' ); ?>>
			<?php
			foreach ( $contact_buttons as $key => $contact_button ) {
				// Ensure attributes are cleared for this key
				$this->widget->remove_render_attribute( 'contact-button-' . $key );

				$link = [
					'platform' => $contact_button['contact_buttons_platform'],
					'number' => $contact_button['contact_buttons_number'] ?? '',
					'username' => $contact_button['contact_buttons_username'] ?? '',
					'email_data' => [
						'contact_buttons_mail' => $contact_button['contact_buttons_mail'] ?? '',
						'contact_buttons_mail_subject' => $contact_button['contact_buttons_mail_subject'] ?? '',
						'contact_buttons_mail_body' => $contact_button['contact_buttons_mail_body'] ?? '',
					],
					'viber_action' => $contact_button['contact_buttons_viber_action'] ?? '',
					'url' => $contact_button['contact_buttons_url'] ?? '',
					'location' => $contact_button['contact_buttons_waze'] ?? '',
					'map' => $contact_button['contact_buttons_map'] ?? '',
				];

				$icon = $contact_button['contact_buttons_icon'];

				$button_classnames = [ self::LAYOUT_CLASSNAME . '__contact-button' ];

				if ( ! empty( $hover_animation ) ) {
					$button_classnames[] = 'elementor-animation-' . $hover_animation;
				}

				$this->widget->add_render_attribute( 'contact-button-' . $key, [
					'aria-label' => esc_attr( $contact_button['contact_buttons_label'] ),
					'class' => $button_classnames,
				] );

				if ( $ehp_platforms->is_url_link( $contact_button['contact_buttons_platform'] ) ) {
					$ehp_platforms->render_link_attributes( $link, 'contact-button-' . $key );
				} else {
					$formatted_link = $ehp_platforms->get_formatted_link( $link, 'contact_icon' );

					$this->widget->add_render_attribute( 'contact-button-' . $key, [
						'href' => $formatted_link,
						'rel' => 'noopener noreferrer',
						'target' => '_blank',
					] );
				}
				?>

				<a <?php $this->widget->print_render_attribute_string( 'contact-button-' . $key ); ?>>
				<?php if ( 'icon' === $link_type ) {
					Icons_Manager::render_icon( $icon,
						[
							'aria-hidden' => 'true',
							'class' => self::LAYOUT_CLASSNAME . '__contact-button-icon',
						]
					);
				} ?>
				<?php if ( 'label' === $link_type ) {
					$this->render_contact_button_text( $contact_button, $key );
				} ?>
				</a>
			<?php } ?>
		</div>
		<?php
	}

	protected function render_contact_button_text( $contact_button, $key ) {
		$label_repeater_key = $this->widget->public_get_repeater_setting_key(
			'contact_buttons_label',
			'contact_buttons_repeater',
			$key
		);

		$this->widget->remove_render_attribute( $label_repeater_key );

		$this->widget->public_add_inline_editing_attributes( $label_repeater_key, 'none' );

		Widget_Utils::maybe_render_text_html(
			$this->widget,
			$label_repeater_key,
			self::LAYOUT_CLASSNAME . '__contact-button-label',
			$contact_button['contact_buttons_label'],
			'span'
		);
		?>
		<?php
	}

	public static function build_email_link( array $data, string $prefix ) {
		$email = $data[ $prefix . '_mail' ] ?? '';
		$subject = $data[ $prefix . '_mail_subject' ] ?? '';
		$body = $data[ $prefix . '_mail_body' ] ?? '';

		if ( ! $email ) {
			return '';
		}

		$link = 'mailto:' . $email;

		if ( $subject ) {
			$link .= '?subject=' . $subject;
		}

		if ( $body ) {
			$link .= $subject ? '&' : '?';
			$link .= 'body=' . $body;
		}

		return $link;
	}

	public static function build_viber_link( string $action, string $number ) {
		if ( empty( $number ) ) {
			return '';
		}

		return add_query_arg( [
			'number' => rawurlencode( $number ),
		], 'viber://' . $action );
	}

	public static function build_messenger_link( string $username ) {
		return 'https://m.me/' . $username;
	}

	protected function render_button( $type ) {
		$button = new Ehp_Button( $this->widget, [
			'type' => $type,
			'widget_name' => 'header',
		] );
		$button->render();
	}

	public function handle_link_classes( $atts, $item, $args, $depth ) {
		$classes = [
			self::LAYOUT_CLASSNAME . '__item',
			$depth ? self::LAYOUT_CLASSNAME . '__item--sub-level' : self::LAYOUT_CLASSNAME . '__item--top-level',
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

		$dropdown_classnames = [ self::LAYOUT_CLASSNAME . '__dropdown' ];
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
					'class' => self::LAYOUT_CLASSNAME . '__submenu-toggle-icon',
				]
			);

			$button_classes = self::LAYOUT_CLASSNAME . '__item ' . self::LAYOUT_CLASSNAME . '__dropdown-toggle';

			$item_output = '<button type="button" class="' . $button_classes . '" aria-expanded="false">' . esc_html( $item->title ) . $svg_icon . '</button>';
		}

		return $item_output;
	}

	public function get_and_advance_nav_menu_index(): int {
		return $this->nav_menu_index++;
	}

	public function __construct( Ehp_Header $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}
}
