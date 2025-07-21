<?php

namespace HelloPlus\Modules\TemplateParts\Classes\Render;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Icons_Manager;
use HelloPlus\Modules\TemplateParts\Widgets\Ehp_Header;

/**
 * class Render_Menu_Cart
 */
class Render_Menu_Cart {

	protected Ehp_Header $widget;

	protected array $settings;

	protected string $layout_classname;

	public function __construct( Ehp_Header $widget, string $layout_classname ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
		$this->layout_classname = $layout_classname;
	}

	public function render(): void {
		$menu_cart_icon = $this->settings['menu_cart_icon'];

		if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'WC' ) || ! WC()->cart || 'no' === $this->settings['menu_cart_icon_show'] ) {
			return;
		}

		$this->widget->remove_render_attribute( 'menu-cart' );
		$this->widget->add_render_attribute( 'menu-cart', [
			'class' => $this->layout_classname . '__menu-cart',
		] );

		$this->widget->remove_render_attribute( 'menu-cart-button' );
		$this->widget->add_render_attribute( 'menu-cart-button', [
			'class' => $this->layout_classname . '__menu-cart-button',
			'type' => 'button',
			'aria-label' => esc_html__( 'Cart', 'hello-plus' ),
		] );

		?>
		<div <?php $this->widget->print_render_attribute_string( 'menu-cart' ); ?>>
			<button <?php $this->widget->print_render_attribute_string( 'menu-cart-button' ); ?>>
				<?php
				if ( ! empty( $menu_cart_icon['value'] ) && ! empty( $menu_cart_icon['library'] ) ) {
					Icons_Manager::render_icon( $menu_cart_icon, [
						'aria-hidden' => 'true',
					] );
				} else {
					Icons_Manager::render_icon(
						[
							'library' => 'eicons',
							'value' => 'eicon-basket-medium',
						]
					);
				}
				?>
			</button>
			<div class="<?php echo esc_attr( $this->layout_classname . '__menu-cart-items' ); ?>" inert>
				<div class="<?php echo esc_attr( $this->layout_classname . '__menu-cart-close-container' ); ?>">
					<button class="<?php echo esc_attr( $this->layout_classname . '__menu-cart-close' ); ?>" aria-label="<?php esc_html_e( 'Close Menu Cart', 'hello-plus' ); ?>">
						<?php
						Icons_Manager::render_icon(
							[
								'library' => 'eicons',
								'value' => 'eicon-close',
							]
						);
						?>
					</button>
				</div>

				<?php
				$cart = WC()->cart;
				if ( ! $cart->is_empty() ) :
					?>
					<ul class="<?php echo esc_attr( $this->layout_classname . '__menu-cart-list' ); ?>">
						<?php

						foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

							if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) {
								$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
								$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
								$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
								?>
								<li class="<?php echo esc_attr( $this->layout_classname . '__menu-cart-item' ); ?>">
									<?php
									$thumbnail = $_product->get_image( 'thumbnail' );
									?>
									<div class="<?php echo esc_attr( $this->layout_classname . '__menu-cart-item-info' ); ?>">
										<a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>" class="<?php echo esc_attr( $this->layout_classname . '__menu-cart-item-remove' ); ?>" aria-label="<?php echo esc_attr__( 'Remove this item', 'hello-plus' ); ?>" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-cart_item_key="<?php echo esc_attr( $cart_item_key ); ?>">
											&times;
										</a>
										<?php if ( ! empty( $thumbnail ) ) : ?>
											<div class="<?php echo esc_attr( $this->layout_classname . '__menu-cart-item-thumbnail' ); ?>">
												<?php echo wp_kses_post( $thumbnail ); ?>
											</div>
										<?php endif; ?>
										<div class="<?php echo esc_attr( $this->layout_classname . '__menu-cart-item-info-content' ); ?>">
											<?php if ( ! empty( $product_permalink ) ) : ?>
												<a href="<?php echo esc_url( $product_permalink ); ?>" class="<?php echo esc_attr( $this->layout_classname . '__menu-cart-item-name' ); ?> <?php echo esc_attr( $this->layout_classname . '__item' ); ?>">
													<?php echo wp_kses_post( $product_name ); ?>
												</a>
											<?php else : ?>
												<span class="<?php echo esc_attr( $this->layout_classname . '__menu-cart-item-name' ); ?>">
													<?php echo wp_kses_post( $product_name ); ?>
												</span>
											<?php endif; ?>
											<span class="<?php echo esc_attr( $this->layout_classname . '__menu-cart-item-price' ); ?>">
												<?php echo esc_html( $cart_item['quantity'] ); ?> &times;
												<?php echo wp_kses_post( $product_price ); ?>
											</span>
										</div>
									</div>
								</li>
								<?php
							}
						}
						?>
					</ul>
					<div class="<?php echo esc_attr( $this->layout_classname . '__menu-cart-subtotal' ); ?>">
						<span><?php esc_html_e( 'Subtotal:', 'hello-plus' ); ?></span>
						<?php echo wp_kses_post( $cart->get_cart_subtotal() ); ?>
					</div>
					<div class="<?php echo esc_attr( $this->layout_classname . '__menu-cart-actions' ); ?>">
						<a class="<?php echo esc_attr( $this->layout_classname . '__menu-cart-view-cart' ); ?> <?php echo esc_attr( $this->layout_classname . '__item' ); ?>" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
							<?php esc_html_e( 'View Cart', 'hello-plus' ); ?>
						</a>
						<a class="<?php echo esc_attr( $this->layout_classname . '__item' ); ?> <?php echo esc_attr( $this->layout_classname . '__menu-cart-checkout' ); ?>" href="<?php echo esc_url( wc_get_checkout_url() ); ?>">
							<?php esc_html_e( 'Checkout', 'hello-plus' ); ?>
						</a>
					</div>
				<?php else : ?>
					<div class="<?php echo esc_attr( $this->layout_classname . '__menu-cart-empty' ); ?>">
						<?php esc_html_e( 'No products in the cart.', 'hello-plus' ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
} 