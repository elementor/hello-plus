<?php

namespace HelloPlus\Modules\TemplateParts\Classes\Render;

use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Utils;

use HelloPlus\Modules\TemplateParts\Widgets\Footer;

class Widget_Footer_Render {
	protected Footer $widget;
	const LAYOUT_CLASSNAME = 'ehp-footer';
	const SITE_LINK_CLASSNAME = 'ehp-footer__site-link';

	protected array $settings;

	public function __construct( Footer $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}

	public function render(): void {
		$layout_classnames = self::LAYOUT_CLASSNAME;
		$box_border = $this->settings['footer_box_border'] ?? '';

        if ( 'yes' === $box_border ) {
			$layout_classnames .= ' has-box-border';
		}

		$this->widget->add_render_attribute( 'layout', [
			'class' => $layout_classnames,
		] );
		?>
		<div <?php echo $this->widget->get_render_attribute_string( 'layout' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<div class="ehp-footer__side-container">
				<?php
					$this->render_site_link();
					$this->render_side_content();
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
		<a <?php echo $this->widget->get_render_attribute_string( 'site-link' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php if ( $site_logo_image ) { ?>
				<?php Group_Control_Image_Size::print_attachment_image_html( $this->settings, 'site_logo_image' ); ?>
			<?php } else {
				$site_title_output = sprintf( '<%1$s %2$s %3$s>%4$s</%1$s>', Utils::validate_html_tag( $site_title_tag ), $this->widget->get_render_attribute_string( 'heading' ), 'class="ehp-footer__site-title"', esc_html( $site_title_text ) );
				// Escaped above
				Utils::print_unescaped_internal_string( $site_title_output );
			} ?>
		</a>
		<?php
	}

	public function render_side_content(): void {
		$description_text = $this->settings['footer_description'];
		$description_tag = $this->settings['footer_description_tag'] ?? 'p';
		$has_description = '' !== $description_text;
		$copyright_text = $this->settings['footer_copyright'];
		$copyright_tag = $this->settings['footer_copyright_tag'] ?? 'p';
		$has_copyright = '' !== $copyright_text;

		$this->widget->add_render_attribute( 'description', [
			'class' => 'ehp-footer__description',
		] );

		$this->widget->add_render_attribute( 'copyright', [
			'class' => 'ehp-footer__copyright',
		] );
		?>
		<div <?php echo $this->widget->get_render_attribute_string( 'description' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php if ( $has_description ) {
				$description_output = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $description_tag ), 'class="ehp-footer__description"', esc_html( $description_text ) );
				// Escaped above
				Utils::print_unescaped_internal_string( $description_output );
			} ?>
			<?php $this->render_social_icons(); ?>
			<?php if ( $has_copyright ) {
				$copyright_output = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $copyright_tag ), 'class="ehp-footer__copyright"', esc_html( $copyright_text ) );
				// Escaped above
				Utils::print_unescaped_internal_string( $copyright_output );
			} ?>
		</div>
		<?php
	}

	public function render_social_icons(): void {
		$icons = $this->settings['footer_icons'] ?? [];

		if ( empty( $icons ) ) {
			return;
		}

		$this->widget->add_render_attribute( 'icons', [
			'class' => 'ehp-footer__icons',
		] );
		?>
		<div <?php echo $this->widget->get_render_attribute_string( 'icons' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php
			foreach ( $icons as $key => $icon ) {
				$link = $icon['footer_icon_link'];
				$text = $icon['footer_icon_text'];
				$icon = $icon['footer_selected_icon'];

				$icon_classnames = 'ehp-footer__social-icon';

				$this->widget->add_render_attribute( 'icon-' . $key, [
					'class' => $icon_classnames,
					'aria-label' => esc_html( $text ),
				] );

				$this->widget->add_link_attributes( 'icon-' . $key, $link );
				?>

				<?php if ( ! empty( $text ) ) : ?>
					<a <?php echo $this->widget->get_render_attribute_string( 'icon-' . $key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php if ( ! empty( $icon['value'] ) ) : ?>
							<span class="ehp-footer__icon"><?php Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ); ?></span>
						<?php endif; ?>
					</a>
				<?php endif; ?>
			<?php } ?>
		</div>
		<?php
	}

    public function get_link_url(): array {
		return [ 'url' => $this->widget->get_site_url() ];
	}
}
