window.addEventListener( 'load', () => {
	const dismissNotice = document.querySelector( '.notice.hello-plus-install-elementor button.notice-dismiss' );
	dismissNotice.addEventListener( 'click', async ( event ) => {
		event.preventDefault();

		const formData = new FormData();
		formData.append( 'action', 'hello_plus_set_admin_notice_viewed' );
		formData.append( 'dismiss_nonce', helloPlusNoticeData.nonce );

		await fetch( helloPlusNoticeData.ajaxurl, { method: 'POST', body: formData } );
	} );
} );
