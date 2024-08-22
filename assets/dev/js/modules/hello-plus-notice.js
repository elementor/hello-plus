import '../../scss/modules/hello-plus-notice.scss';

window.addEventListener( 'load', () => {
	const dismissNotice = document.querySelector( '.notice.hello-plus-install-elementor button.notice-dismiss' );
	dismissNotice.addEventListener( 'click', async ( event ) => {
		event.preventDefault();

		const formData = new FormData();
		formData.append( 'action', 'hello_plus_set_admin_notice_viewed' );
		formData.append( 'dismiss_nonce', '<?php echo esc_js( wp_create_nonce( self::DISMISS_INSTALL_NOTICE_NONCE ) ); ?>' );

		await fetch( ajaxurl, { method: 'POST', body: formData } );
	} );
} );
