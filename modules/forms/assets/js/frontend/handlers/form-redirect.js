export default elementorModules.frontend.handlers.Base.extend( {
	getDefaultSettings() {
		return {
			selectors: {
				form: '.ehp-form',
			},
		};
	},

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );
		return {
			$form: this.$element[ 0 ].querySelector( selectors.form ),
			$submitButton: this.$element[ 0 ].querySelector( selectors.submitButton ),
		};
	},

	bindEvents() {
		this.elements.$form.addEventListener( 'form_destruct', this.handleSubmit.bind( this ) );
	},

	handleSubmit( event ) {
		const { detail: { data: { redirect_url: redirectUrl = '' } = {} } = {} } = event;

		if ( redirectUrl ) {
			location.href = redirectUrl;
		}
	},
} );
