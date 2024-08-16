<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @return void
 */
function hello_plus_fail_load_admin_notice() {
	// Leave to Elementor Pro to manage this.
	if ( function_exists( 'elementor_pro_load_plugin' ) ) {
		return;
	}

	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	if ( 'true' === get_user_meta( get_current_user_id(), '_hello_plus_install_notice', true ) ) {
		return;
	}

	$plugin = 'elementor/elementor.php';

	$installed_plugins = get_plugins();

	$is_elementor_installed = isset( $installed_plugins[ $plugin ] );

	$message = esc_html__( 'The Hello Plus is a lightweight starter theme that works perfectly with the Elementor award-winning site builder plugin.', 'hello-plus' );

	if ( $is_elementor_installed ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$message .= ' ' . esc_html__( 'Once you activate the plugin, you are only one click away from building an amazing website.', 'hello-plus' );

		$button_text = esc_html__( 'Activate Elementor', 'hello-plus' );
		$button_link = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$message .= ' ' . esc_html__( 'Once you download and activate the plugin, you are only one click away from building an amazing website.', 'hello-plus' );

		$button_text = esc_html__( 'Install Elementor', 'hello-plus' );
		$button_link = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
	}

	?>
	<style>
		.notice.hello-plus-notice {
			border: 1px solid #ccd0d4;
			border-inline-start: 4px solid #9b0a46 !important;
			box-shadow: 0 1px 4px rgba(0,0,0,0.15);
			display: flex;
			padding: 0;
		}
		.notice.hello-plus-notice.hello-plus-install-elementor {
			padding: 0;
		}
		.notice.hello-plus-notice .hello-plus-notice-aside {
			display: flex;
			align-items: start;
			justify-content: center;
			padding: 20px 10px;
			background: rgba(215,43,63,0.04);
		}
		.notice.hello-plus-notice .hello-plus-notice-aside img {
			width: 1.5rem;
		}
		.notice.hello-plus-notice .hello-plus-notice-content {
			display: flex;
			flex-direction: column;
			gap: 5px;
			padding: 20px;
			width: 100%;
		}
		.notice.hello-plus-notice .hello-plus-notice-content h3,
		.notice.hello-plus-notice .hello-plus-notice-content p {
			padding: 0;
			margin: 0;
		}
		.notice.hello-plus-notice .hello-plus-information-link {
			align-self: start;
		}
		.notice.hello-plus-notice .hello-plus-install-button {
			align-self: start;
			background-color: #127DB8;
			border-radius: 3px;
			color: #fff;
			text-decoration: none;
			height: auto;
			line-height: 20px;
			padding: 0.4375rem 0.75rem;
			margin-block-start: 15px;
		}
		.notice.hello-plus-notice .hello-plus-install-button:active {
			transform: translateY(1px);
		}
		@media (max-width: 767px) {
			.notice.hello-plus-notice .hello-plus-notice-aside {
				padding: 10px;
			}
			.notice.hello-plus-notice .hello-plus-notice-content {
				gap: 10px;
				padding: 10px;
			}
		}
	</style>
	<script>
		window.addEventListener( 'load', () => {
			const dismissNotice = document.querySelector( '.notice.hello-plus-install-elementor button.notice-dismiss' );
			dismissNotice.addEventListener( 'click', async ( event ) => {
				event.preventDefault();

				var formData = new FormData();
				formData.append( 'action', 'hello_plus_set_admin_notice_viewed' );
				formData.append( 'dismiss_nonce', '<?php echo esc_js( wp_create_nonce( 'hello_plus_dismiss_install_notice' ) ); ?>' );

				await fetch( ajaxurl, { method: 'POST', body: formData } );
			} );
		} );
	</script>
	<div class="notice updated is-dismissible hello-plus-notice hello-plus-install-elementor">
		<div class="hello-plus-notice-aside">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/elementor-notice-icon.svg' ); ?>" alt="<?php echo esc_attr__( 'Get Elementor', 'hello-plus' ); ?>" />
		</div>
		<div class="hello-plus-notice-content">
			<h3><?php echo esc_html__( 'Thanks for installing the Hello Plus Theme!', 'hello-plus' ); ?></h3>
			<p><?php echo esc_html( $message ); ?></p>
			<a class="hello-plus-information-link" href="https://go.elementor.com/hello-plus-learn/" target="_blank"><?php echo esc_html__( 'Explore Elementor Site Builder Plugin', 'hello-plus' ); ?></a>
			<a class="hello-plus-install-button" href="<?php echo esc_attr( $button_link ); ?>"><?php echo esc_html( $button_text ); ?></a>
		</div>
	</div>
	<?php
}

/**
 * Set dismissed admin notice as viewed.
 *
 * @return void
 */
function ajax_hello_plus_set_admin_notice_viewed() {
	check_ajax_referer( 'hello_plus_dismiss_install_notice', 'dismiss_nonce' );

	update_user_meta( get_current_user_id(), '_hello_plus_install_notice', 'true' );
	die;
}
add_action( 'wp_ajax_hello_plus_set_admin_notice_viewed', 'ajax_hello_plus_set_admin_notice_viewed' );

if ( ! did_action( 'elementor/loaded' ) ) {
	add_action( 'admin_notices', 'hello_plus_fail_load_admin_notice' );
}
