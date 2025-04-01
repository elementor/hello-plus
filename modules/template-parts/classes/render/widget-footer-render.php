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

	protected int $nav_menu_index = 1;

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

		$this->widget->maybe_add_advanced_attributes();

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

	public function render_side_content(): void {
		$this->widget->add_render_attribute( 'side-content', 'class', self::LAYOUT_CLASSNAME . '__side-content' );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'side-content' ); ?>>
			<?php
			$this->widget->render_site_link( 'footer' );

			Widget_Utils::maybe_render_text_html( $this->widget, 'footer_description', self::LAYOUT_CLASSNAME . '__description', $this->settings['footer_description'], $this->settings['footer_description_tag'] ?? 'p' );
			?>
			<?php $this->render_social_icons(); ?>
		</div>
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

		if ( ! $available_menus ) {
			return;
		}

		$args = [
			'echo' => false,
			'menu' => $this->settings['navigation_menu'],
			'menu_class' => self::LAYOUT_CLASSNAME . '__menu',
			'menu_id' => 'menu-' . $this->get_and_advance_nav_menu_index() . '-' . $this->widget->get_id(),
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

	public function get_and_advance_nav_menu_index(): int {
		return $this->nav_menu_index++;
	}

	public function __construct( Ehp_Footer $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}
}
