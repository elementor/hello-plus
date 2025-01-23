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
use HelloPlus\Classes\Ehp_Button;

/**
 * class Widget_Header_Render
 */
class Widget_Header_Render {
	const LAYOUT_CLASSNAME = 'ehp-header';
	const SITE_LINK_CLASSNAME = 'ehp-header__site-link';
	const CTAS_CONTAINER_CLASSNAME = 'ehp-header__ctas-container';
	const BUTTON_CLASSNAME = 'ehp-header__button';

	protected Ehp_Header $widget;

	protected array $settings;

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
		$behavior_float_shape = $this->settings['behavior_float_shape'];
		$behavior_float_shape_tablet = $this->settings['behavior_float_shape_tablet'] ?? '';
		$behavior_float_shape_mobile = $this->settings['behavior_float_shape_mobile'] ?? '';
		$has_blur_background = $this->settings['blur_background'];

		if ( ! empty( $navigation_breakpoint ) ) {
			$layout_classnames[] = 'has-navigation-breakpoint-' . $navigation_breakpoint;
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

		if ( ! empty( $behavior_float_shape ) ) {
			$layout_classnames[] = 'has-shape-' . $behavior_float_shape;

			if ( ! empty( $behavior_float_shape_mobile ) ) {
				$layout_classnames[] = 'has-shape-sm-' . $behavior_float_shape_mobile;
			}

			if ( ! empty( $behavior_float_shape_tablet ) ) {
				$layout_classnames[] = 'has-shape-md-' . $behavior_float_shape_tablet;
			}
		}

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

		$this->maybe_add_advanced_attributes();

		?>
		<header <?php $this->widget->print_render_attribute_string( 'layout' ); ?>>
			<div class="ehp-header__elements-container">
				<?php
				$this->render_site_link();
				$this->render_navigation();
				$this->render_ctas_container();
				?>
			</div>
		</header>
		<?php
	}

	protected function maybe_add_advanced_attributes() {
		$advanced_css_id = $this->settings['advanced_custom_css_id'];
		$advanced_css_classes = $this->settings['advanced_custom_css_classes'];

		$wrapper_render_attributes = [];
		if ( ! empty( $advanced_css_classes ) ) {
			$wrapper_render_attributes['class'] = $advanced_css_classes;
		}

		if ( ! empty( $advanced_css_id ) ) {
			$wrapper_render_attributes['id'] = $advanced_css_id;
		}
		if ( empty( $wrapper_render_attributes ) ) {
			return;
		}
		$this->widget->add_render_attribute( '_wrapper', $wrapper_render_attributes );
	}

	public function render_site_link(): void {
		$site_logo_brand_select = $this->settings['site_logo_brand_select'];

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
			<?php if ( 'logo' === $site_logo_brand_select ) {
				Group_Control_Image_Size::print_attachment_image_html( $this->settings, 'site_logo_image' );
			} ?>
			<?php if ( 'title' === $site_logo_brand_select ) {
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
		$toggle_classname = 'ehp-header__button-toggle';

		$this->widget->add_render_attribute( 'button-toggle', [
			'class' => $toggle_classname,
			'role' => 'button',
			'tabindex' => '0',
			'aria-label' => esc_html__( 'Menu Toggle', 'hello-plus' ),
			'aria-expanded' => 'false',
		] );

		?>
		<div class="ehp-header__side-toggle">
			<?php if ( 'yes' === $this->settings['contact_buttons_show'] ) {
				$this->render_contact_buttons();
			} ?>
			<button <?php $this->widget->print_render_attribute_string( 'button-toggle' ); ?>>
				<span class="ehp-header__toggle-icon ehp-header__toggle-icon--open" aria-hidden="true">
					<?php
					Icons_Manager::render_icon( $toggle_icon,
						[
							'role' => 'presentation',
						]
					);
					?>
				</span>
				<i class="eicon-close ehp-header__toggle-icon ehp-header__toggle-icon--close"></i>
				<span class="elementor-screen-only"><?php esc_html_e( 'Menu', 'hello-plus' ); ?></span>
			</button>
		</div>
		<?php
	}

	protected function render_ctas_container() {
		$responsive_button_width = $this->settings['cta_responsive_width'] ?? '';
		$ctas_container_classnames = self::CTAS_CONTAINER_CLASSNAME;

		if ( '' !== $responsive_button_width ) {
			$ctas_container_classnames .= ' has-responsive-width-' . $responsive_button_width;
		}

		$this->widget->add_render_attribute( 'ctas-container', [
			'class' => $ctas_container_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'ctas-container' ); ?>>
			<?php
			if ( 'yes' === $this->settings['contact_buttons_show'] ) {
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
			'ehp-header__contact-buttons',
			'has-responsive-display-' . $responsive_display,
		];

		$this->widget->add_render_attribute( 'contact-buttons', [
			'class' => $contact_buttons_classnames,
		] );

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

				$button_classnames = [ 'ehp-header__contact-button' ];

				if ( ! empty( $hover_animation ) ) {
					$button_classnames[] = 'elementor-animation-' . $hover_animation;
				}

				$this->widget->add_render_attribute( 'contact-button-' . $key, [
					'aria-label' => esc_attr( $contact_button['contact_buttons_label'] ),
					'class' => $button_classnames,
				] );

				if ( $this->is_url_link( $contact_button['contact_buttons_platform'] ) ) {
					$this->render_link_attributes( $link, 'contact-button-' . $key );
				} else {
					$formatted_link = $this->get_formatted_link( $link, 'contact_icon' );

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
							'class' => 'ehp-header__contact-button-icon',
						]
					);
				} ?>
				<?php if ( 'label' === $link_type ) { ?>
					<span class="ehp-header__contact-button-label"><?php echo esc_html( $contact_button['contact_buttons_label'] ); ?></span>
				<?php } ?>
				</a>
			<?php } ?>
		</div>
		<?php
	}

	protected function is_url_link( $platform ) {
		return 'url' === $platform || 'waze' === $platform || 'map' === $platform;
	}

	protected function render_link_attributes( array $link, string $key ) {
		switch ( $link['platform'] ) {
			case 'waze':
				if ( empty( $link['location']['url'] ) ) {
					$link['location']['url'] = '#';
				}

				$this->widget->add_link_attributes( $key, $link['location'] );
				break;
			case 'url':
				if ( empty( $link['url']['url'] ) ) {
					$link['url']['url'] = '#';
				}

				$this->widget->add_link_attributes( $key, $link['url'] );
				break;
			case 'map':
				if ( empty( $link['map']['url'] ) ) {
					$link['map']['url'] = '#';
				}

				$this->widget->add_link_attributes( $key, $link['map'] );
				break;
			default:
				break;
		}
	}

	protected function get_formatted_link( array $link, string $prefix ): string {

		// Ensure we clear the default link value if the matching type value is empty
		switch ( $link['platform'] ) {
			case 'email':
				$formatted_link = $this->build_email_link( $link['email_data'], $prefix );
				break;
			case 'sms':
				$formatted_link = ! empty( $link['number'] ) ? 'sms:' . $link['number'] : '';
				break;
			case 'messenger':
				$formatted_link = ! empty( $link['username'] ) ?
					$this->build_messenger_link( $link['username'] ) :
					'';
				break;
			case 'whatsapp':
				$formatted_link = ! empty( $link['number'] ) ? 'https://wa.me/' . $link['number'] : '';
				break;
			case 'viber':
				$formatted_link = $this->build_viber_link( $link['viber_action'], $link['number'] );
				break;
			case 'skype':
				$formatted_link = ! empty( $link['username'] ) ? 'skype:' . $link['username'] . '?chat' : '';
				break;
			case 'telephone':
				$formatted_link = ! empty( $link['number'] ) ? 'tel:' . $link['number'] : '';
				break;
			default:
				break;
		}

		return esc_html( $formatted_link );
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
		$button = new Ehp_Button( $this->widget, [ 'type' => $type, 'widget_name' => 'header' ] );
		$button->render();
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

	public function handle_sub_menu_classes() {
		$submenu_layout = $this->settings['style_submenu_layout'] ?? 'horizontal';
		$submenu_shape = $this->settings['style_submenu_shape'];

		$dropdown_classnames = [ 'ehp-header__dropdown' ];
		$dropdown_classnames[] = 'has-layout-' . $submenu_layout;

		if ( ! empty( $submenu_shape ) ) {
			$dropdown_classnames[] = 'has-shape-' . $submenu_shape;
		}

		return $dropdown_classnames;
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

	public function __construct( Ehp_Header $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}
}
