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

use HelloPlus\Modules\TemplateParts\Widgets\Ehp_Flex_Footer;

use HelloPlus\Classes\{
	Ehp_Shapes,
	Widget_Utils,
	Ehp_Social_Platforms,
};

/**
 * class Widget_Flex_Footer_Render
 */
class Widget_Flex_Footer_Render {
	protected Ehp_Flex_Footer $widget;
	const LAYOUT_CLASSNAME = 'ehp-flex-footer';

	protected array $settings;

	protected int $nav_menu_index = 1;

	public function render(): void {
		$layout_classnames = [
			self::LAYOUT_CLASSNAME,
			'has-preset-' . $this->settings['layout_preset'],
		];

		if ( 'yes' === $this->settings['style_layout_align_center_mobile'] ) {
			$layout_classnames[] = 'is-align-center-mobile';
		}

		$this->widget->add_render_attribute( 'layout', [
			'class' => $layout_classnames,
		] );

		$this->widget->maybe_add_advanced_attributes();

		$this->widget->add_render_attribute( 'groups-row', 'class', self::LAYOUT_CLASSNAME . '__groups-row' );
		?>
		<footer <?php $this->widget->print_render_attribute_string( 'layout' ); ?>>
			<div <?php $this->widget->print_render_attribute_string( 'groups-row' ); ?>>
				<?php
					$this->render_business_details();

				if ( 'yes' === $this->settings['group_2_switcher'] ) {
					$this->render_footer_group( '2' );
				}
				if ( 'yes' === $this->settings['group_3_switcher'] ) {
					$this->render_footer_group( '3' );
				}
				if ( 'yes' === $this->settings['group_4_switcher'] ) {
					$this->render_footer_group( '4' );
				}
				?>
			</div>

			<?php $this->render_copyright(); ?>
		</footer>
		<?php
	}

	public function render_business_details(): void {
		$this->widget->add_render_attribute( 'business-details', [
			'class' => [
				self::LAYOUT_CLASSNAME . '__group',
				self::LAYOUT_CLASSNAME . '__group--business-details',
			],
		] );

		$description_classnames = self::LAYOUT_CLASSNAME . '__description ' . self::LAYOUT_CLASSNAME . '__description--business-details';
		?>
		<div <?php $this->widget->print_render_attribute_string( 'business-details' ); ?>>
			<?php
				$this->widget->render_site_link( 'flex-footer' );
				$this->render_subheading( 1, 'business_details' );
				Widget_Utils::maybe_render_text_html( $this->widget, 'group_1_business_details_description', $description_classnames, $this->settings['group_1_business_details_description'] );
			?>
		</div>
		<?php
	}

	public function render_footer_group( $group_number ) {
		$group_type = $this->settings[ 'group_' . $group_number . '_type' ];
		?>
		<?php
		if ( 'contact-links' === $group_type ) {
			$this->render_contact_links_group( $group_number );
		} elseif ( 'text' === $group_type ) {
			$this->render_text_group( $group_number );
		} elseif ( 'social-links' === $group_type ) {
			$this->render_social_links_group( $group_number );
		} elseif ( 'navigation-links' === $group_type ) {
			$this->render_navigation_links_group( $group_number );
		}
		?>
		<?php
	}

	protected function render_contact_links_group( $group_number ) {
		$ehp_platforms = new Ehp_Social_Platforms( $this->widget );
		$repeater = $this->settings[ 'group_' . $group_number . '_repeater' ];

		$this->widget->remove_render_attribute( 'contact-links-group' );
		$this->widget->add_render_attribute( 'contact-links-group', [
			'class' => [
				self::LAYOUT_CLASSNAME . '__group',
				self::LAYOUT_CLASSNAME . '__group--contact-links',
			],
		] );
		$this->widget->add_render_attribute( 'contact-links-list', [
			'class' => [
				self::LAYOUT_CLASSNAME . '__list',
				self::LAYOUT_CLASSNAME . '__list--contact-links',
			],
		] );
		$this->widget->add_render_attribute( 'contact-link', [
			'class' => [
				self::LAYOUT_CLASSNAME . '__list-item',
				self::LAYOUT_CLASSNAME . '__list-item--contact-link',
			],
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'contact-links-group' ); ?>>
			<?php $this->render_subheading( $group_number, 'contact_links' ); ?>
			<ul <?php $this->widget->print_render_attribute_string( 'contact-links-list' ); ?>>
				<?php
				foreach ( $repeater as $key => $contact_link ) {
					$link = [
						'platform' => $contact_link[ 'group_' . $group_number . '_platform' ],
						'number'   => $contact_link[ 'group_' . $group_number . '_number' ] ?? '',
						'username' => $contact_link[ 'group_' . $group_number . '_username' ] ?? '',
						'email_data' => [
							'group_' . $group_number . '_mail'         => $contact_link[ 'group_' . $group_number . '_mail' ] ?? '',
							'group_' . $group_number . '_mail_subject' => $contact_link[ 'group_' . $group_number . '_mail_subject' ] ?? '',
							'group_' . $group_number . '_mail_body'    => $contact_link[ 'group_' . $group_number . '_mail_body' ] ?? '',
						],
						'viber_action' => $contact_link[ 'group_' . $group_number . '_viber_action' ] ?? '',
						'url'          => $contact_link[ 'group_' . $group_number . '_url' ] ?? '',
						'location'     => $contact_link[ 'group_' . $group_number . '_waze' ] ?? '',
						'map'          => $contact_link[ 'group_' . $group_number . '_map' ] ?? '',
					];

					$hover_animation = $this->settings['style_business_details_links_hover_animation'];

					$contact_link_classnames = [
						self::LAYOUT_CLASSNAME . '__link',
						self::LAYOUT_CLASSNAME . '__link--contact',
					];

					if ( ! empty( $hover_animation ) ) {
						$contact_link_classnames[] = 'elementor-animation-' . $hover_animation;
					}

					$this->widget->add_render_attribute( 'contact-link-' . $key, [
						'aria-label' => esc_attr( $contact_link[ 'group_' . $group_number . '_label' ] ),
						'class'      => $contact_link_classnames,
					] );

					if ( $ehp_platforms->is_url_link( $contact_link[ 'group_' . $group_number . '_platform' ] ) ) {
						$ehp_platforms->render_link_attributes( $link, 'contact-link-' . $key );
					} else {
						$formatted_link = $ehp_platforms->get_formatted_link( $link, 'group_' . $group_number );

						$this->widget->add_render_attribute( 'contact-link-' . $key, [
							'href' => $formatted_link,
							'rel'  => 'noopener noreferrer',
						] );
					}

					$label_repeater_key = $this->widget->public_get_repeater_setting_key(
						'group_' . $group_number . '_label',
						'group_' . $group_number . '_repeater',
						$key
					);

					$this->widget->public_add_inline_editing_attributes( $label_repeater_key, 'none' );
					?>
					<li <?php $this->widget->print_render_attribute_string( 'contact-link' ); ?>>
						<a <?php $this->widget->print_render_attribute_string( 'contact-link-' . $key ); ?>>
							<?php
							Widget_Utils::maybe_render_text_html(
								$this->widget,
								$label_repeater_key,
								self::LAYOUT_CLASSNAME . '__contact-link-label',
								$contact_link[ 'group_' . $group_number . '_label' ],
								'span'
							);
							?>
						</a>
					</li>
					<?php
				}
				?>
			</ul>
		</div>
		<?php
	}

	protected function render_text_group( $group_number ) {
		$settings = $this->widget->get_settings_for_display();

		$this->widget->remove_render_attribute( 'group-text' );
		$this->widget->add_render_attribute( 'group-text', [
			'class' => [
				self::LAYOUT_CLASSNAME . '__group',
				self::LAYOUT_CLASSNAME . '__group--text',
			],
		] );

		$description_classnames = self::LAYOUT_CLASSNAME . '__description ' . self::LAYOUT_CLASSNAME . '__description--text';

		?>
		<div <?php $this->widget->print_render_attribute_string( 'group-text' ); ?>>
			<?php
			$this->render_subheading( $group_number, 'text' );

			Widget_Utils::maybe_render_text_html(
				$this->widget,
				'group_' . $group_number . '_text_textarea',
				$description_classnames,
				$settings[ 'group_' . $group_number . '_text_textarea' ]
			);
			?>
		</div>
		<?php
	}

	protected function render_social_links_group( $group_number ) {
		$repeater = $this->settings[ 'group_' . $group_number . '_social_repeater' ];

		$this->widget->remove_render_attribute( 'social-links-group' );
		$this->widget->add_render_attribute( 'social-links-group', [
			'class' => [
				self::LAYOUT_CLASSNAME . '__group',
				self::LAYOUT_CLASSNAME . '__group--social-links',
			],
		] );
		$this->widget->add_render_attribute( 'social-links-list', [
			'class' => [
				self::LAYOUT_CLASSNAME . '__list',
				self::LAYOUT_CLASSNAME . '__list--social-links',
			],
		] );
		$this->widget->add_render_attribute( 'social-link', [
			'class' => [
				self::LAYOUT_CLASSNAME . '__list-item',
				self::LAYOUT_CLASSNAME . '__list-item--social-link',
			],
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'social-links-group' ); ?>>
			<?php $this->render_subheading( $group_number, 'social_links' ); ?>

			<ul <?php $this->widget->print_render_attribute_string( 'social-links-list' ); ?>>
				<?php foreach ( $repeater as $key => $social_icon ) {
					$icon = $social_icon[ 'group_' . $group_number . '_social_icon' ] ?? [];
					$label = $social_icon[ 'group_' . $group_number . '_social_label' ] ?? '';
					$url = $social_icon[ 'group_' . $group_number . '_social_link' ] ?? [];
					$hover_animation = $this->settings['style_business_details_links_hover_animation'];

					$social_icon_classnames = [
						self::LAYOUT_CLASSNAME . '__link',
						self::LAYOUT_CLASSNAME . '__link--social',
					];

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
					<li <?php $this->widget->print_render_attribute_string( 'social-link' ); ?>>
						<a <?php $this->widget->print_render_attribute_string( 'social-icon-' . $key ); ?>>
							<?php
							Icons_Manager::render_icon( $icon, [
								'aria-hidden' => 'true',
								'class' => self::LAYOUT_CLASSNAME . '__social-icon',
							] ); ?>
						</a>
					</li>
				<?php } ?>
			</ul>
		</div>
		<?php
	}

	protected function render_navigation_links_group( $group_number ) {
		$this->widget->remove_render_attribute( 'navigation-links-group' );
		$this->widget->add_render_attribute( 'navigation-links-group', [
			'class' => [
				self::LAYOUT_CLASSNAME . '__group',
				self::LAYOUT_CLASSNAME . '__group--navigation-links',
			],
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'navigation-links-group' ); ?>>
			<?php $this->render_subheading( $group_number, 'navigation_links' ); ?>
			<?php $this->render_navigation( $group_number ); ?>
		</div>
		<?php
	}

	protected function render_subheading( $group_number, $subheading_type ) {
		$subheading_text = $this->settings[ 'group_' . $group_number . '_' . $subheading_type . '_subheading' ];

		if ( $subheading_text ) {
			$subheading_tag = $this->settings['subheading_tag'];
			$subheading_classname = self::LAYOUT_CLASSNAME . '__subheading';

			Widget_Utils::maybe_render_text_html( $this->widget, 'group_' . $group_number . '_' . $subheading_type . '_subheading', $subheading_classname, $subheading_text, $subheading_tag );
		}
	}

	public function render_navigation( $group_number ): void {
		$available_menus = $this->widget->get_available_menus();

		if ( ! $available_menus ) {
			return;
		}

		$args = [
			'echo' => false,
			'menu' => $this->settings[ 'footer_navigation_menu_' . $group_number ],
			'menu_class' => self::LAYOUT_CLASSNAME . '__menu ' . self::LAYOUT_CLASSNAME . '__list',
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

		$this->widget->add_render_attribute( 'nav-container', 'class', self::LAYOUT_CLASSNAME . '__nav-container' );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'nav-container' ); ?>>
			<nav <?php $this->widget->print_render_attribute_string( 'main-menu' ); ?>>
				<?php

				add_filter( 'nav_menu_link_attributes', [ $this, 'handle_link_classes' ], 10, 4 );

				$args['echo'] = true;

				wp_nav_menu( $args );

				remove_filter( 'nav_menu_link_attributes', [ $this, 'handle_link_classes' ] );
				?>
			</nav>
		</div>
		<?php
	}

	public function render_copyright(): void {
		$this->widget->add_render_attribute( 'copyright', 'class', self::LAYOUT_CLASSNAME . '__copyright' );
		$this->widget->add_render_attribute( 'copyright-wrapper', 'class', self::LAYOUT_CLASSNAME . '__copyright-wrapper' );
		$this->widget->add_render_attribute( 'copyright-prefix', 'class', self::LAYOUT_CLASSNAME . '__copyright-prefix' );
		$this->widget->add_render_attribute( 'copyright-text-container', 'class', self::LAYOUT_CLASSNAME . '__copyright-text-container' );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'copyright' ); ?>>
			<div <?php $this->widget->print_render_attribute_string( 'copyright-wrapper' ); ?>>
				<div <?php $this->widget->print_render_attribute_string( 'copyright-text-container' ); ?>>
					<span <?php $this->widget->print_render_attribute_string( 'copyright-prefix' ); ?>>
						<?php
						if ( ! empty( $this->settings['current_year_switcher'] ) && 'yes' === $this->settings['current_year_switcher'] ) {
							echo wp_kses_post( '&copy;' . esc_html( gmdate( 'Y' ) ) . '.&nbsp;' );
						}
						?>
					</span>
					<?php
					Widget_Utils::maybe_render_text_html(
						$this->widget,
						'copyright_text',
						self::LAYOUT_CLASSNAME . '__copyright-text',
						$this->settings['copyright_text'],
						'span',
					);
					?>
				</div>
			</div>
		</div>
		<?php
	}

	public function handle_link_classes( $atts, $item ) {
		$classes = [
			self::LAYOUT_CLASSNAME . '__menu-item',
			self::LAYOUT_CLASSNAME . '__link',
		];
		$is_anchor = false !== strpos( $atts['href'], '#' );
		$hover_animation = $this->settings['style_business_details_links_hover_animation'];

		if ( $hover_animation ) {
			$classes[] = 'elementor-animation-' . $hover_animation;
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

	public function __construct( Ehp_Flex_Footer $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}
}
