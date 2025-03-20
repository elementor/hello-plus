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

use HelloPlus\Modules\TemplateParts\Widgets\Ehp_Footer;

use HelloPlus\Classes\{
	Ehp_Shapes,
	Widget_Utils,
};

/**
 * class Widget_Footer_Render
 */
class Widget_Footer_Render {
	protected Ehp_Footer $widget;
	const LAYOUT_CLASSNAME = 'ehp-footer';

	protected array $settings;

	public function render(): void {
		$layout_classnames = self::LAYOUT_CLASSNAME;
		$box_border = $this->settings['footer_box_border'] ?? '';

		if ( 'yes' === $box_border ) {
			$layout_classnames .= ' has-box-border';
		}

		$render_attributes = [
			'class' => $layout_classnames,
		];

		$this->widget->add_render_attribute( 'layout', $render_attributes );

		$this->maybe_add_advanced_attributes();

		$this->widget->add_render_attribute( 'row', 'class', self::LAYOUT_CLASSNAME . '__row' );

		?>
		<footer <?php $this->widget->print_render_attribute_string( 'layout' ); ?>>
			<div <?php $this->widget->print_render_attribute_string( 'row' ); ?>>
				<?php
					$this->render_side_content();
					$this->render_navigation();
					$this->render_contact();
				?>
			</div>
			<?php $this->render_copyright(); ?>
		</footer>
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

	public function render_side_content(): void {
		$description_text = $this->settings['footer_description'];
		$description_tag = $this->settings['footer_description_tag'] ?? 'p';
		$has_description = '' !== $description_text;

		$this->widget->add_render_attribute( 'footer_description', [
			'class' => self::LAYOUT_CLASSNAME . '__description',
		] );
		$this->widget->add_render_attribute( 'side-content', 'class', self::LAYOUT_CLASSNAME . '__side-content' );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'side-content' ); ?>>
			<?php $this->render_site_link(); ?>
			<?php if ( $has_description ) {
				$element_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $description_tag ), $this->widget->get_render_attribute_string( 'footer_description' ), esc_html( $description_text ) );

				// Escaped above
				Utils::print_unescaped_internal_string( $element_html );
			} ?>
			<?php $this->render_social_icons(); ?>
		</div>
		<?php
	}

	public function get_attachment_image_html_filter( $html ) {
		$logo_classnames = [
			self::LAYOUT_CLASSNAME . '__site-logo',
		];

		if ( ! empty( $this->settings['show_logo_border'] ) && 'yes' === $this->settings['show_logo_border'] ) {
			$logo_classnames[] = 'has-border';
		}

		$shapes = new Ehp_Shapes( $this->widget, [
			'container_prefix' => 'logo',
			'widget_name' => 'footer',
		] );

		$logo_classnames = array_merge( $logo_classnames, $shapes->get_shape_classnames() );

		$html = str_replace( '<img ', '<img class="' . esc_attr( implode( ' ', $logo_classnames ) ) . '" ', $html );
		return $html;
	}

	public function render_site_link(): void {
		$site_logo_image = $this->settings['site_logo_image'];
		$site_link_classnames = self::LAYOUT_CLASSNAME . '__site-link';
		$site_title_tag = $this->settings['site_logo_title_tag'] ?? 'h2';

		$this->widget->add_render_attribute( 'site-link', [
			'class' => $site_link_classnames,
		] );

		$site_link = $this->get_link_url();

		if ( $site_link ) {
			$this->widget->add_link_attributes( 'site-link', $site_link );
		}

		if ( $site_logo_image ) {
			$this->settings['site_logo_image'] = $this->widget->add_site_logo_if_present( $this->settings['site_logo_image'] );
		}

		$site_title_classname = self::LAYOUT_CLASSNAME . '__site-title';

		?>
		<a <?php $this->widget->print_render_attribute_string( 'site-link' ); ?>>
			<?php if ( $site_logo_image ) {
				add_filter( 'elementor/image_size/get_attachment_image_html', [ $this, 'get_attachment_image_html_filter' ], 10, 4 );
				Group_Control_Image_Size::print_attachment_image_html( $this->settings, 'site_logo_image' );
				remove_filter( 'elementor/image_size/get_attachment_image_html', [ $this, 'get_attachment_image_html_filter' ], 10 );
			} else {
				Widget_Utils::maybe_render_text_html( $this->widget, 'footer_site_title', $site_title_classname,  $this->widget->get_site_title(), $site_title_tag );
			} ?>
		</a>
		<?php
	}

	public function render_social_icons(): void {
		$icons = $this->settings['footer_icons'] ?? [];
		$icon_hover_animation = $this->settings['social_icons_hover_animation'] ?? '';
		$footer_icons_classnames = self::LAYOUT_CLASSNAME . '__social-icons';

		if ( empty( $icons ) ) {
			return;
		}

		$this->widget->add_render_attribute( 'icons', [
			'class' => $footer_icons_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'icons' ); ?>>
			<?php
			foreach ( $icons as $key => $icon ) {
				$link = $icon['footer_icon_link'];
				$text = $icon['footer_icon_text'];
				$selected_icon = $icon['footer_selected_icon'];

				$icon_classnames = self::LAYOUT_CLASSNAME . '__social-icon';

				if ( $icon_hover_animation ) {
					$icon_classnames .= ' elementor-animation-' . $icon_hover_animation;
				}

				$this->widget->add_render_attribute( 'icon-' . $key, [
					'class' => $icon_classnames,
					'aria-label' => esc_html( $text ),
				] );

				$this->widget->add_link_attributes( 'icon-' . $key, $link );
				?>

				<?php if ( ! empty( $text ) ) : ?>
					<a <?php $this->widget->print_render_attribute_string( 'icon-' . $key ); ?>>
						<?php if ( ! empty( $selected_icon['value'] ) ) : ?>
							<?php Icons_Manager::render_icon( $selected_icon, [ 'aria-hidden' => 'true' ] ); ?>
						<?php endif; ?>
					</a>
				<?php endif; ?>
			<?php } ?>
		</div>
		<?php
	}

	public function render_navigation(): void {
		$available_menus = $this->widget->get_available_menus();
		$menu_classname = self::LAYOUT_CLASSNAME . '__menu';

		if ( ! $available_menus ) {
			return;
		}

		$args = [
			'echo' => false,
			'menu' => $this->settings['navigation_menu'],
			'menu_class' => $menu_classname,
			'menu_id' => 'menu-' . $this->widget->get_and_advance_nav_menu_index() . '-' . $this->widget->get_id(),
			'fallback_cb' => '__return_empty_string',
			'container' => '',
			'depth' => 1,
		];

		add_filter( 'nav_menu_link_attributes', [ $this, 'handle_link_classes' ], 10, 4 );

		$menu_html = wp_nav_menu( $args );

		remove_filter( 'nav_menu_link_attributes', [ $this, 'handle_link_classes' ] );

		if ( empty( $menu_html ) ) {
			return;
		}

		$this->widget->add_render_attribute( 'main-menu', [
			'class' => self::LAYOUT_CLASSNAME . '__navigation',
			'aria-label' => $this->settings['footer_menu_heading'],
		] );

		$this->widget->add_render_attribute( 'nav-container', 'class', self::LAYOUT_CLASSNAME . '__nav-container' );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'nav-container' ); ?>>
			<nav <?php $this->widget->print_render_attribute_string( 'main-menu' ); ?>>
				<?php
				Widget_Utils::maybe_render_text_html( $this->widget, 'footer_menu_heading', self::LAYOUT_CLASSNAME . '__menu-heading', $this->settings['footer_menu_heading'], $this->settings['footer_menu_heading_tag'] ?? 'h6' );

				add_filter( 'nav_menu_link_attributes', [ $this, 'handle_link_classes' ], 10, 4 );

				$args['echo'] = true;

				wp_nav_menu( $args );

				remove_filter( 'nav_menu_link_attributes', [ $this, 'handle_link_classes' ] );
				?>
			</nav>
		</div>
		<?php
	}

	public function render_contact(): void {
		$this->widget->add_render_attribute( 'contact', [
			'class' => self::LAYOUT_CLASSNAME . '__contact',
		] );
		$this->widget->add_render_attribute( 'contact-container', 'class', self::LAYOUT_CLASSNAME . '__contact-container' );

		$contact_heading_classname = self::LAYOUT_CLASSNAME . '__contact-heading';
		$contact_information_classname = self::LAYOUT_CLASSNAME . '__contact-information';
		?>
		<div <?php $this->widget->print_render_attribute_string( 'contact-container' ); ?>>
			<div <?php $this->widget->print_render_attribute_string( 'contact' ); ?>>
				<?php
					Widget_Utils::maybe_render_text_html( $this->widget, 'footer_contact_heading', $contact_heading_classname, $this->settings['footer_contact_heading'], $this->settings['footer_contact_heading_tag'] );
					Widget_Utils::maybe_render_text_html( $this->widget, 'footer_contact_information', $contact_information_classname, $this->settings['footer_contact_information'], $this->settings['footer_contact_information_tag'] );
				?>
			</div>
		</div>
		<?php
	}

	public function render_copyright(): void {
		$this->widget->add_render_attribute( 'footer-copyright', 'class', self::LAYOUT_CLASSNAME . '__copyright' );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'footer-copyright' ); ?>>
			<?php
			Widget_Utils::maybe_render_text_html( $this->widget, 'footer_copyright', self::LAYOUT_CLASSNAME . '__copyright', $this->settings['footer_copyright'], $this->settings['footer_copyright_tag'] ?? 'p' );
			?>
		</div>
		<?php
	}

	public function get_link_url(): array {
		return [ 'url' => $this->widget->get_site_url() ];
	}

	public function handle_link_classes( $atts, $item ) {
		$classes = [ self::LAYOUT_CLASSNAME . '__menu-item' ];
		$is_anchor = false !== strpos( $atts['href'], '#' );
		$has_hover_animation = $this->settings['style_navigation_hover_animation'] ?? '';

		if ( $has_hover_animation ) {
			$classes[] = 'elementor-animation-' . $has_hover_animation;
		}

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

	public function __construct( Ehp_Footer $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}
}
