module.exports = elementorModules.editor.utils.Module.extend( {
	eventName: 'site_mailer_forms_email_notice',
	suffix: '',
	control: null,
	onSectionActive( sectionName ) {
		// Check if the section is the email section
		if ( ! [ 'section_email', 'section_email_2' ].includes( sectionName ) ) {
			return;
		}

		this.suffix = 'section_email_2' === sectionName ? '_2' : '';
		this.control = null;

		// Check if control exists
		if ( ! this.hasPromoControl() ) {
			return;
		}

		// Check if the user has dismissed the hint
		if ( elementor.config.user.dismissed_editor_notices.includes( 'site_mailer_forms_email_notice' ) ) {
			this.getPromoControl().remove();
			return;
		}

		this.registerEvents();
	},

	registerEvents() {
		// Handle dismiss and action buttons
		const dismissBtn = this.getPromoControl().$el.find( '.elementor-control-notice-dismiss' );
		const onDismissBtnClick = ( event ) => {
			dismissBtn.off( 'click', onDismissBtnClick ); // Remove the event listener
			event.preventDefault();
			this.dismiss();
			this.getPromoControl().remove();
		};
		dismissBtn.on( 'click', onDismissBtnClick );

		// Handle action button
		const actionBtn = this.getPromoControl().$el.find( '.e-btn-1' );
		const onActionBtn = ( event ) => {
			actionBtn.off( 'click', onActionBtn ); // Remove the event listener
			event.preventDefault();
			this.onAction( event );
			this.getPromoControl().remove();
		};
		actionBtn.on( 'click', onActionBtn );
	},

	getPromoControl() {
		if ( ! this.control ) {
			this.control = this.getEditorControlView( 'site_mailer_promo' + this.suffix );
		}
		return this.control;
	},

	hasPromoControl() {
		return !! this.getPromoControl();
	},

	ajaxRequest( name, data ) {
		elementorCommon.ajax.addRequest( name, {
			data,
		} );
	},

	dismiss() {
		this.ajaxRequest( 'dismissed_editor_notices', {
			dismissId: this.eventName,
		} );

		// Prevent opening the same hint again in current editor session.
		this.ensureNoPromoControlInSession();
	},

	ensureNoPromoControlInSession() {
		// Prevent opening the same hint again in current editor session.
		elementor.config.user.dismissed_editor_notices.push( this.eventName );
	},

	onAction( event ) {
		const { action_url: actionURL = null } = JSON.parse( event.target.closest( 'button' ).dataset.settings );
		if ( actionURL ) {
			window.open( actionURL, '_blank' );
		}

		this.ajaxRequest( 'elementor_site_mailer_campaign', {
			source: 'sm-form-install',
		} );

		this.ensureNoPromoControlInSession();
	},

	onInit() {
		elementor.channels.editor.on( 'section:activated', ( sectionName ) => this.onSectionActive( sectionName ) );
	},
} );
