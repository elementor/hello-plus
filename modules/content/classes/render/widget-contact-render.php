<?php
namespace HelloPlus\Modules\Content\Classes\Render;

use HelloPlus\Modules\Content\Widgets\Contact;
use HelloPlus\Classes\{
	Ehp_Button,
	Ehp_Image,
	Ehp_Shapes,
	Ehp_Social_Platforms,
};

use Elementor\{
	Icons_Manager,
	Utils,
};

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Widget_Contact_Render {
	protected Contact $widget;
	const LAYOUT_CLASSNAME = 'ehp-contact';

	protected array $settings;

	public function render(): void {
		$layout_classnames = [
			self::LAYOUT_CLASSNAME,
			'has-preset-' . $this->settings['layout_preset'],
		];
		$layout_full_height_controls = $this->settings['box_full_screen_height_controls'] ?? '';
		$show_map = 'quick-info' !== $this->settings['layout_preset'];

		$this->widget->add_render_attribute( 'layout', [
			'class' => $layout_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'layout' ); ?>>
			<?php
				$this->render_text_container();
				
				if ( $show_map ) {
					$this->render_map_container();
				}
			?>
		</div>
		<?php
	}

	protected function render_text_container() {
		$heading_text = $this->settings['heading_text'];
		$heading_tag = $this->settings['heading_tag'];

		$description_text = $this->settings['description_text'];
		$description_tag = $this->settings['description_tag'];

		$text_container_classnames = [
			self::LAYOUT_CLASSNAME . '__text-container',
		];
		$heading_classname = self::LAYOUT_CLASSNAME . '__heading';
		$description_classname = self::LAYOUT_CLASSNAME . '__description';

		$this->widget->add_render_attribute( 'text-container', [
			'class' => $text_container_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'text-container' ); ?>>
			<div class="ehp-contact__headings">
				<?php if ( '' !== $heading_text ) {
					$heading_output = sprintf( '<%1$s class="%2$s">%3$s</%1$s>', Utils::validate_html_tag( $heading_tag ), $heading_classname, esc_html( $heading_text ) );
					// Escaped above
					Utils::print_unescaped_internal_string( $heading_output );
				} ?>
				<?php if ( '' !== $description_text ) {
					$description_output = sprintf( '<%1$s class="%2$s">%3$s</%1$s>', Utils::validate_html_tag( $description_tag ), $description_classname, esc_html( $description_text ) );
					// Escaped above
					Utils::print_unescaped_internal_string( $description_output );
				} ?>
			</div>
			<div class="ehp-contact__groups">
				<?php
					$this->render_contact_group( '1' );
					
					if ( 'yes' === $this->settings['group_2_switcher'] ) {
						$this->render_contact_group( '2' );
					}
					if ( 'yes' === $this->settings['group_3_switcher'] ) {
						$this->render_contact_group( '3' );
					}
					if ( 'yes' === $this->settings['group_4_switcher'] ) {
						$this->render_contact_group( '4' );
					}
				?>
			</div>
		</div>
		<?php
	}

	protected function render_contact_group( $group_number ) {
		$group_type = $this->settings['group_' . $group_number . '_type'];
		?>
		<div class="ehp-contact__group">
			<?php
				if ( 'contact-links' === $group_type ) {
					$this->render_contact_links_group( $group_number );
				} else if ( 'text' === $group_type ) {
					$this->render_contact_text_group( $group_number );
				} else if ( 'social-icons' === $group_type ) {
					$this->render_social_icons_group( $group_number );
				}
			?>
		</div>
		<?php
	}

	protected function render_contact_links_group( $group_number ) {
		$subheading_text = $this->settings['group_' . $group_number . '_links_subheading'];

		?>
		<div class="ehp-contact__group-links">
			<?php if ( '' !== $subheading_text ) {
				$subheading_output = sprintf( '<h3 class="ehp-contact__group-links-subheading">%s</h3>', esc_html( $subheading_text ) );
				// Escaped above
				Utils::print_unescaped_internal_string( $subheading_output );
			} ?>
			<?php $this->render_contact_links( $group_number ); ?>
		</div>
		<?php
	}

	protected function render_contact_links( $group_number ) {
		$repeater = $this->settings[ 'group_' . $group_number . '_repeater' ];
		$hover_animation = $this->settings['contact_details_social_icon_hover_animation'];

		$ehp_platforms = new Ehp_Social_Platforms( $this->widget );
		?>
		<div class="ehp-contact__links-container">
			<?php
			foreach ( $repeater as $key => $contact_link ) {
				$link = [
					'platform' => $contact_link['group_' . $group_number . '_platform'],
					'number' => $contact_link['group_' . $group_number . '_number'] ?? '',
					'username' => $contact_link['group_' . $group_number . '_username'] ?? '',
					'email_data' => [
						'group_' . $group_number . '_mail' => $contact_link['group_' . $group_number . '_mail'] ?? '',
						'group_' . $group_number . '_mail_subject' => $contact_link['group_' . $group_number . '_mail_subject'] ?? '',
						'group_' . $group_number . '_mail_body' => $contact_link['group_' . $group_number . '_mail_body'] ?? '',
					],
					'viber_action' => $contact_link['group_' . $group_number . '_viber_action'] ?? '',
					'url' => $contact_link['group_' . $group_number . '_url'] ?? '',
					'location' => $contact_link['group_' . $group_number . '_waze'] ?? '',
					'map' => $contact_link['group_' . $group_number . '_map'] ?? '',
				];
	
				$icon = $contact_link['group_' . $group_number . '_icon'];
	
				$contact_link_classnames = [ 'ehp-contact__contact-link' ];
	
				if ( ! empty( $hover_animation ) ) {
					$contact_link_classnames[] = 'elementor-animation-' . $hover_animation;
				}
	
				$this->widget->add_render_attribute( 'contact-link-' . $key, [
					'aria-label' => esc_attr( $contact_link['group_' . $group_number . '_label'] ),
					'class' => $contact_link_classnames,
				] );
	
				if ( $ehp_platforms->is_url_link( $contact_link['group_' . $group_number . '_platform'] ) ) {
					$ehp_platforms->render_link_attributes( $link, 'contact-link-' . $key );
				} else {
					$formatted_link = $ehp_platforms->get_formatted_link( $link, 'contact_icon' );
	
					$this->widget->add_render_attribute( 'contact-link-' . $key, [
						'href' => $formatted_link,
						'rel' => 'noopener noreferrer',
					] );
				}
				
				?>
				<a <?php $this->widget->print_render_attribute_string( 'contact-link-' . $key ); ?>>
						<?php Icons_Manager::render_icon( $icon,
							[
								'aria-hidden' => 'true',
								'class' => 'ehp-contact__contact-link-icon',
							]
						); ?>
						<span class="ehp-contact__contact-link-label"><?php echo esc_html( $contact_link['group_' . $group_number . '_label'] ); ?></span>
					</a>
				<?php
			}
			?>
		</div>
		<?php
	}

	protected function render_contact_text_group( $group_number ) {
		$subheading_text = $this->settings['group_' . $group_number . '_text_subheading'];
		?>
		<div class="ehp-contact__group-text">
			<?php if ( '' !== $subheading_text ) {
				$subheading_output = sprintf( '<h3 class="ehp-contact__group-text-subheading">%s</h3>', esc_html( $subheading_text ) );
				// Escaped above
				Utils::print_unescaped_internal_string( $subheading_output );
			} ?>
		</div>
		<?php
	}

	protected function render_social_icons_group( $group_number ) {
		$subheading_text = $this->settings['group_' . $group_number . '_social_subheading'];
		?>
		<div class="ehp-contact__group-social">
			<?php if ( '' !== $subheading_text ) {
				$subheading_output = sprintf( '<h3 class="ehp-contact__group-social-subheading">%s</h3>', esc_html( $subheading_text ) );
				// Escaped above
				Utils::print_unescaped_internal_string( $subheading_output );
			} ?>
		</div>
		<?php
	}

	protected function render_map_container() {
		$map_container_classnames = [
			self::LAYOUT_CLASSNAME . '__map-container',
		];

		$this->widget->add_render_attribute( 'map-container', [
			'class' => $map_container_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'map-container' ); ?>>
			Map container
		</div>
		<?php
	}

	public function __construct( Contact $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}
}