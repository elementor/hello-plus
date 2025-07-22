<?php

namespace HelloPlus\Modules\TemplateParts\Classes\Render\Header;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Icons_Manager;
use HelloPlus\Classes\Render_Base;

/**
 * class Render_Menu_Cart
 */
class Render_Menu_Cart extends Render_Base {

	public function render(): void {
		if ( ! $this->should_render_cart() ) {
			return;
		}

		$this->setup_render_attributes();

		?>
		<div <?php $this->widget->print_render_attribute_string( 'menu-cart' ); ?>>
			<?php $this->render_cart_button(); ?>
			<?php $this->render_cart_dropdown(); ?>
		</div>
		<?php
	}


	protected function should_render_cart(): bool {
		return class_exists( 'WooCommerce' )
			&& function_exists( 'WC' )
			&& 'yes' === $this->settings['menu_cart_icon_show'];
	}

	protected function setup_render_attributes(): void {
		$this->widget->remove_render_attribute( 'menu-cart' );
		$this->widget->add_render_attribute( 'menu-cart', [
			'class' => $this->get_class_name( '__menu-cart' ),
		] );

		$this->widget->remove_render_attribute( 'menu-cart-button' );
		$this->widget->add_render_attribute( 'menu-cart-button', [
			'class' => $this->get_class_name( '__menu-cart-button' ),
			'type' => 'button',
			'aria-label' => esc_html__( 'Cart', 'hello-plus' ),
		] );
	}

	protected function render_cart_button(): void {
		?>
		<button <?php $this->widget->print_render_attribute_string( 'menu-cart-button' ); ?>>
			<?php $this->render_cart_icon(); ?>
		</button>
		<?php
	}

	protected function render_cart_icon(): void {
		$menu_cart_icon = $this->settings['menu_cart_icon'];

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
	}

	protected function render_cart_dropdown(): void {
		?>
		<div class="<?php echo esc_attr( $this->get_class_name( '__menu-cart-items' ) ); ?>" inert>
			<?php $this->render_cart_close_button(); ?>
			<?php $this->render_cart_content(); ?>
		</div>
		<?php
	}

	protected function render_cart_close_button(): void {
		?>
		<div class="<?php echo esc_attr( $this->get_class_name( '__menu-cart-close-container' ) ); ?>">
			<button class="<?php echo esc_attr( $this->get_class_name( '__menu-cart-close' ) ); ?>" aria-label="<?php esc_html_e( 'Close Menu Cart', 'hello-plus' ); ?>">
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
	}


	protected function render_cart_content(): void {
		$cart = WC()->cart;

		if ( $cart && ! $cart->is_empty() ) {
			$this->render_cart_items( $cart );
			$this->render_cart_summary( $cart );
		} else {
			$this->render_empty_cart();
		}
	}


	protected function render_cart_items( $cart ): void {
		?>
		<ul class="<?php echo esc_attr( $this->get_class_name( '__menu-cart-list' ) ); ?>">
			<?php
			foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
				$this->render_single_cart_item( $cart_item_key, $cart_item );
			}
			?>
		</ul>
		<?php
	}

	protected function render_single_cart_item( string $cart_item_key, array $cart_item ): void {
		$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		$product_id = (int) apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

		if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 ) {
			return;
		}

		$product_data = $this->get_product_data( $_product, $cart_item, $cart_item_key );

		?>
		<li class="<?php echo esc_attr( $this->get_class_name( '__menu-cart-item' ) ); ?>">
			<div class="<?php echo esc_attr( $this->get_class_name( '__menu-cart-item-info' ) ); ?>">
				<?php $this->render_item_remove_button( $cart_item_key, $product_id ); ?>
				<?php $this->render_item_thumbnail( $_product ); ?>
				<?php $this->render_item_content( $product_data, $cart_item ); ?>
			</div>
		</li>
		<?php
	}

	protected function get_product_data( $_product, array $cart_item, string $cart_item_key ): array {
		return [
			'name' => apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ),
			'price' => apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ),
			'permalink' => apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key ),
		];
	}


	protected function render_item_remove_button( string $cart_item_key, int $product_id ): void {
		?>
		<a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>" 
			class="<?php echo esc_attr( $this->get_class_name( '__menu-cart-item-remove' ) ); ?>" 
			aria-label="<?php echo esc_attr__( 'Remove this item', 'hello-plus' ); ?>" 
			data-product_id="<?php echo esc_attr( $product_id ); ?>" 
			data-cart_item_key="<?php echo esc_attr( $cart_item_key ); ?>">
			&times;
		</a>
		<?php
	}

	protected function render_item_thumbnail( $_product ): void {
		$thumbnail = $_product->get_image( 'thumbnail' );

		if ( ! empty( $thumbnail ) ) : ?>
			<div class="<?php echo esc_attr( $this->get_class_name( '__menu-cart-item-thumbnail' ) ); ?>">
				<?php echo wp_kses_post( $thumbnail ); ?>
			</div>
		<?php endif;
	}

	protected function render_item_content( array $product_data, array $cart_item ): void {
		?>
		<div class="<?php echo esc_attr( $this->get_class_name( '__menu-cart-item-info-content' ) ); ?>">
			<?php $this->render_item_name( $product_data ); ?>
			<?php $this->render_item_price( $product_data, $cart_item ); ?>
		</div>
		<?php
	}

	protected function render_item_name( array $product_data ): void {
		if ( ! empty( $product_data['permalink'] ) ) : ?>
			<a href="<?php echo esc_url( $product_data['permalink'] ); ?>" 
				class="<?php echo esc_attr( $this->get_class_name( '__menu-cart-item-name' ) ); ?> <?php echo esc_attr( $this->get_class_name( '__item' ) ); ?>">
				<?php echo wp_kses_post( $product_data['name'] ); ?>
			</a>
		<?php else : ?>
			<span class="<?php echo esc_attr( $this->get_class_name( '__menu-cart-item-name' ) ); ?>">
				<?php echo wp_kses_post( $product_data['name'] ); ?>
			</span>
		<?php endif;
	}

	protected function render_item_price( array $product_data, array $cart_item ): void {
		?>
		<span class="<?php echo esc_attr( $this->get_class_name( '__menu-cart-item-price' ) ); ?>">
			<?php echo esc_html( $cart_item['quantity'] ); ?> &times;
			<?php echo wp_kses_post( $product_data['price'] ); ?>
		</span>
		<?php
	}

	protected function render_cart_summary( $cart ): void {
		$this->render_cart_subtotal( $cart );
		$this->render_cart_actions();
	}

	protected function render_cart_subtotal( $cart ): void {
		?>
		<div class="<?php echo esc_attr( $this->get_class_name( '__menu-cart-subtotal' ) ); ?>">
			<span><?php esc_html_e( 'Subtotal:', 'hello-plus' ); ?></span>
			<?php echo wp_kses_post( $cart->get_cart_subtotal() ); ?>
		</div>
		<?php
	}

	protected function render_cart_actions(): void {
		?>
		<div class="<?php echo esc_attr( $this->get_class_name( '__menu-cart-actions' ) ); ?>">
			<a class="<?php echo esc_attr( $this->get_class_name( '__menu-cart-view-cart' ) ); ?> <?php echo esc_attr( $this->get_class_name( '__item' ) ); ?>" 
				href="<?php echo esc_url( wc_get_cart_url() ); ?>">
				<?php esc_html_e( 'View Cart', 'hello-plus' ); ?>
			</a>
			<a class="<?php echo esc_attr( $this->get_class_name( '__item' ) ); ?> <?php echo esc_attr( $this->get_class_name( '__menu-cart-checkout' ) ); ?>" 
				href="<?php echo esc_url( wc_get_checkout_url() ); ?>">
				<?php esc_html_e( 'Checkout', 'hello-plus' ); ?>
			</a>
		</div>
		<?php
	}

	protected function render_empty_cart(): void {
		?>
		<div class="<?php echo esc_attr( $this->get_class_name( '__menu-cart-empty' ) ); ?>">
			<?php esc_html_e( 'No products in the cart.', 'hello-plus' ); ?>
		</div>
		<?php
	}
}
