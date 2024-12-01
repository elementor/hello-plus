window.addEventListener( 'load', () => {
	const dismissNotice = document.querySelector( '.notice.hello-plus-install-elementor button.notice-dismiss' );
	if ( dismissNotice ) {
		dismissNotice.addEventListener( 'click', async ( event ) => {
			event.preventDefault();

			const formData = new FormData();
			formData.append( 'action', 'helloplus_set_admin_notice_viewed' );
			formData.append( 'dismiss_nonce', helloPlusNoticeData.nonce );

			await fetch( helloPlusNoticeData.ajaxurl, { method: 'POST', body: formData } );
		} );
	}
} );
