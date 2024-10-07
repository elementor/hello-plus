<?php

namespace HelloPlus\Modules\Header\Classes\Render;

use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

use HelloPlus\Modules\Header\Base\Traits\Shared_Header_Traits;
use HelloPlus\Includes\Utils as Theme_Utils;
use HelloPlus\Modules\Header\Widgets\Header;

class Widget_Header_Render {
	protected Header $widget;
	use Shared_Header_Traits;

	const LAYOUT_CLASSNAME = 'ehp-header';

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
			<?php
				$this->render_site_link();
			?>
		</div>
		<?php
	}

	public function render_site_link(): void {
		$site_link_classnames = 'ehp-header__site-link';
		$site_logo_image = $this->settings['site_logo_image'];
		$site_title_text = $this->get_site_title();
		$site_title_tag = $this->settings['site_logo_title_tag'] ?? 'h2';

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
			<?php } else {
				$site_title_output = sprintf( '<%1$s %2$s %3$s>%4$s</%1$s>', Utils::validate_html_tag( $site_title_tag ), $this->widget->get_render_attribute_string( 'heading' ), 'class="ehp-header__site-title"', esc_html( $site_title_text ) );
				// Escaped above
				Utils::print_unescaped_internal_string( $site_title_output );
			} ?>
		</a>
		<?php
	}

	protected function get_link_url(): array {
		return [ 'url' => $this->get_site_url() ];
	}
}
