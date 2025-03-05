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

		if ( 'yes' === $this->settings['show_box_border'] ) {
			$layout_classnames[] = 'has-border';
		}

		if ( ! empty( $layout_full_height_controls ) ) {
			foreach ( $layout_full_height_controls as $breakpoint ) {
				$layout_classnames[] = ' is-full-height-' . $breakpoint;
			}
		}

		if ( 'yes' === $this->settings['map_stretch'] ) {
			$layout_classnames[] = 'has-map-stretch';
		}

		$elements_container_classnames = [
			self::LAYOUT_CLASSNAME . '__elements-container',
		];

		$map_position_horizontal = $this->settings['map_position_horizontal'];
		$map_position_vertical = $this->settings['map_position_vertical'];

		$shapes = new Ehp_Shapes( $this->widget, [
			'container_prefix' => 'box',
			'render_attribute' => 'layout',
			'widget_name' => $this->widget->get_name(),
		] );
		$shapes->add_shape_attributes();

		if ( ! empty( $map_position_horizontal ) ) {
			$elements_container_classnames[] = 'has-map-h-position-' . $map_position_horizontal;

			if ( ! empty( $map_position_horizontal_tablet ) ) {
				$elements_container_classnames[] = 'has-map-h-position-md-' . $map_position_horizontal_tablet;
			}

			if ( ! empty( $map_position_horizontal_mobile ) ) {
				$elements_container_classnames[] = 'has-map-h-position-sm-' . $map_position_horizontal_mobile;
			}
		}

		if ( ! empty( $map_position_vertical ) ) {
			$elements_container_classnames[] = 'has-map-v-position-' . $map_position_vertical;

			if ( ! empty( $map_position_vertical_tablet ) ) {
				$elements_container_classnames[] = 'has-map-v-position-md-' . $map_position_vertical_tablet;
			}

			if ( ! empty( $map_position_vertical_mobile ) ) {
				$elements_container_classnames[] = 'has-map-v-position-sm-' . $map_position_vertical_mobile;
			}
		}

		$this->widget->add_render_attribute( 'layout', [
			'class' => $layout_classnames,
		] );

		$this->widget->add_render_attribute( 'elements-container', [
			'class' => $elements_container_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'layout' ); ?>>
			<div class="<?php echo esc_html( self::LAYOUT_CLASSNAME ); ?>__overlay"></div>
			<div <?php $this->widget->print_render_attribute_string( 'elements-container' ); ?>>
				<?php
					$this->render_text_container();

				if ( $show_map ) {
					$this->render_map_container();
				}
				?>
			</div>
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
			<div class="<?php echo esc_html( self::LAYOUT_CLASSNAME ); ?>__headings">
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
			<div class="<?php echo esc_html( self::LAYOUT_CLASSNAME ); ?>__groups">
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
		$group_type = $this->settings[ 'group_' . $group_number . '_type' ];
		?>
		<div class="<?php echo esc_html( self::LAYOUT_CLASSNAME ); ?>__group">
			<?php
			if ( 'contact-links' === $group_type ) {
				$this->render_contact_links_group( $group_number );
			} elseif ( 'text' === $group_type ) {
				$this->render_contact_text_group( $group_number );
			} elseif ( 'social-icons' === $group_type ) {
				$this->render_social_icons_group( $group_number );
			}
			?>
		</div>
		<?php
	}

	protected function render_subheading( $group_number, $subheading_type ) {
		$subheading_text = $this->settings[ 'group_' . $group_number . '_' . $subheading_type . '_subheading' ];
		$subheading_tag = $this->settings[ 'subheading_tag' ];

		if ( '' !== $subheading_text ) {
			$subheading_output = sprintf( '<%1$s class="%3$s">%2$s</%1$s>', Utils::validate_html_tag( $subheading_tag ), esc_html( $subheading_text ), self::LAYOUT_CLASSNAME . '__subheading' );
			// Escaped above
			Utils::print_unescaped_internal_string( $subheading_output );
		}
	}

	protected function render_contact_links_group( $group_number ) {
		$this->render_subheading( $group_number, 'links' );
		$this->render_contact_links( $group_number );
	}

	protected function render_contact_links( $group_number ) {
		$repeater = $this->settings[ 'group_' . $group_number . '_repeater' ];
		$hover_animation = $this->settings['contact_details_social_icon_hover_animation'];

		$ehp_platforms = new Ehp_Social_Platforms( $this->widget );
		?>
		<div class="<?php echo esc_html( self::LAYOUT_CLASSNAME ); ?>__links-container">
			<?php
			foreach ( $repeater as $key => $contact_link ) {
				$link = [
					'platform' => $contact_link[ 'group_' . $group_number . '_platform' ],
					'number' => $contact_link[ 'group_' . $group_number . '_number' ] ?? '',
					'username' => $contact_link[ 'group_' . $group_number . '_username' ] ?? '',
					'email_data' => [
						'group_' . $group_number . '_mail' => $contact_link[ 'group_' . $group_number . '_mail' ] ?? '',
						'group_' . $group_number . '_mail_subject' => $contact_link[ 'group_' . $group_number . '_mail_subject' ] ?? '',
						'group_' . $group_number . '_mail_body' => $contact_link[ 'group_' . $group_number . '_mail_body' ] ?? '',
					],
					'viber_action' => $contact_link[ 'group_' . $group_number . '_viber_action' ] ?? '',
					'url' => $contact_link[ 'group_' . $group_number . '_url' ] ?? '',
					'location' => $contact_link[ 'group_' . $group_number . '_waze' ] ?? '',
					'map' => $contact_link[ 'group_' . $group_number . '_map' ] ?? '',
				];

				$icon = $contact_link[ 'group_' . $group_number . '_icon' ];

				$contact_link_classnames = [ self::LAYOUT_CLASSNAME . '__contact-link' ];

				if ( ! empty( $hover_animation ) ) {
					$contact_link_classnames[] = 'elementor-animation-' . $hover_animation;
				}

				$this->widget->add_render_attribute( 'contact-link-' . $key, [
					'aria-label' => esc_attr( $contact_link[ 'group_' . $group_number . '_label' ] ),
					'class' => $contact_link_classnames,
				] );

				if ( $ehp_platforms->is_url_link( $contact_link[ 'group_' . $group_number . '_platform' ] ) ) {
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
								'class' => self::LAYOUT_CLASSNAME . '__contact-link-icon',
							]
						); ?>
						<span class="<?php echo esc_html( self::LAYOUT_CLASSNAME ); ?>__contact-link-label"><?php echo esc_html( $contact_link[ 'group_' . $group_number . '_label' ] ); ?></span>
					</a>
				<?php
			} ?>
		</div>
		<?php
	}

	protected function render_contact_text_group( $group_number ) {
		$text_text = $this->settings['group_' . $group_number . '_text_textarea'];

		$this->render_subheading( $group_number, 'text' );

		if ( '' !== $text_text ) {
			$text_output = sprintf( '<div class="%2$s">%1$s</div>', esc_html( $text_text ), self::LAYOUT_CLASSNAME . '__contact-text' );
			// Escaped above
			Utils::print_unescaped_internal_string( $text_output );
		}
	}

	protected function render_social_icons_group( $group_number ) {
		$repeater = $this->settings[ 'group_' . $group_number . '_social_repeater' ];

		$this->render_subheading( $group_number, 'social' );
		?>
		<div class="<?php echo esc_html( self::LAYOUT_CLASSNAME ); ?>__social-icons-container">
			<?php
			foreach ( $repeater as $key => $social_icon ) {
				$icon = $social_icon[ 'group_' . $group_number . '_social_icon' ] ?? [];
				$label = $social_icon[ 'group_' . $group_number . '_social_label' ] ?? '';
				$url = $social_icon[ 'group_' . $group_number . '_social_link' ] ?? [];
				$hover_animation = $this->settings['contact_details_social_icon_hover_animation'];

				$social_icon_classnames = [ self::LAYOUT_CLASSNAME . '__social-link' ];

				if ( ! empty( $hover_animation ) ) {
					$social_icon_classnames[] = 'elementor-animation-' . $hover_animation;
				}

				$this->widget->add_render_attribute( 'social-icon-' . $key, [
					'aria-label' => esc_attr( $label ),
					'class' => $social_icon_classnames,
					'rel' => 'noopener noreferrer',
				] );

				if ( ! empty( $url['url'] ) ) {
					$this->widget->add_link_attributes( 'social-icon-' . $key, $url );
				}
				?>
				<a <?php $this->widget->print_render_attribute_string( 'social-icon-' . $key ); ?>>
					<?php Icons_Manager::render_icon( $icon,
						[
							'aria-hidden' => 'true',
							'class' => self::LAYOUT_CLASSNAME . '__contact-social-icon',
						]
					); ?>
				</a>
				<?php
			}
			?>
		</div>
		<?php
	}

	protected function render_map_container() {
		$map_container_classnames = [
			self::LAYOUT_CLASSNAME . '__map-container',
		];

		if ( 0 === absint( $this->settings['map_zoom']['size'] ) ) {
			$this->settings['map_zoom']['size'] = 10;
		}

		$api_key = esc_html( get_option( 'elementor_google_maps_api_key' ) );

		$params = [
			rawurlencode( $this->settings['map_address'] ),
			absint( $this->settings['map_zoom']['size'] ),
		];

		if ( $api_key ) {
			$params[] = $api_key;

			$url = 'https://www.google.com/maps/embed/v1/place?key=%3$s&q=%1$s&amp;zoom=%2$d';
		} else {
			$url = 'https://maps.google.com/maps?q=%1$s&amp;t=m&amp;z=%2$d&amp;output=embed&amp;iwloc=near';
		}

		$this->widget->add_render_attribute( 'map-container', [
			'class' => $map_container_classnames,
		] );

		$map_classnames = [
			self::LAYOUT_CLASSNAME . '__map',
			'elementor-custom-embed',
		];

		if ( 'yes' === $this->settings['show_map_border'] ) {
			$map_classnames[] = 'has-border';
		}

		$shapes = new Ehp_Shapes( $this->widget, [
			'container_prefix' => 'map',
			'render_attribute' => 'map',
			'widget_name' => $this->widget->get_name(),
		] );
		$shapes->add_shape_attributes();

		$this->widget->add_render_attribute( 'map', [
			'class' => $map_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'map-container' ); ?>>
			<div <?php $this->widget->print_render_attribute_string( 'map' ); ?>>
				<iframe loading="lazy"
						src="<?php echo esc_url( vsprintf( $url, $params ) ); ?>"
						title="<?php echo esc_attr( $this->settings['map_address'] ); ?>"
						aria-label="<?php echo esc_attr( $this->settings['map_address'] ); ?>"
				></iframe>
			</div>
		</div>
		<?php
	}

	public function __construct( Contact $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}
}
