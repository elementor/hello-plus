export default class Recaptcha extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				recaptcha: '.elementor-g-recaptcha:last',
				submit: 'button[type="submit"]',
				recaptchaResponse: '[name="g-recaptcha-response"]',
			},
		};
	}

	getDefaultElements() {
		const { selectors } = this.getDefaultSettings(),
			elements = {
				$recaptcha: this.$element.find( selectors.recaptcha ),
			};

		elements.$form = elements.$recaptcha.parents( 'form' );
		elements.$submit = elements.$form.find( selectors.submit );

		return elements;
	}

	bindEvents() {
		this.onRecaptchaApiReady();
	}

	isActive( settings ) {
		const { selectors } = this.getDefaultSettings();

		return settings.$element.find( selectors.recaptcha ).length;
	}

	addRecaptcha() {
		const settings = this.elements.$recaptcha.data(),
			isV2 = 'v3' !== settings.type,
			captchaIds = [];

		captchaIds.forEach( ( id ) => window.grecaptcha.reset( id ) );

		const widgetId = window.grecaptcha.render( this.elements.$recaptcha[ 0 ], settings );

		this.elements.$form.on( 'reset error', () => {
			window.grecaptcha.reset( widgetId );
		} );

		if ( isV2 ) {
			this.elements.$recaptcha.data( 'widgetId', widgetId );
		} else {
			captchaIds.push( widgetId );

			this.elements.$submit.on( 'click', ( e ) => this.onV3FormSubmit( e, widgetId ) );
		}
	}

	onV3FormSubmit( e, widgetId ) {
		e.preventDefault();

		window.grecaptcha.ready( () => {
			const $form = this.elements.$form;

			grecaptcha.execute(
				widgetId,
				{ action: this.elements.$recaptcha.data( 'action' ) },
			).then( ( token ) => {
				if ( this.elements.$recaptchaResponse ) {
					this.elements.$recaptchaResponse.val( token );
				} else {
					this.elements.$recaptchaResponse = jQuery( '<input>', {
						type: 'hidden',
						value: token,
						name: 'g-recaptcha-response',
					} );

					$form.append( this.elements.$recaptchaResponse );
				}

				// Support old browsers.
				const bcSupport = ! $form[ 0 ].reportValidity || 'function' !== typeof $form[ 0 ].reportValidity;

				if ( bcSupport || $form[ 0 ].reportValidity() ) {
					$form.trigger( 'submit' );
				}
			} );
		} );
	}

	onRecaptchaApiReady() {
		if ( window.grecaptcha && window.grecaptcha.render ) {
			this.addRecaptcha();
		} else {
			// If not ready check again by timeout..
			setTimeout( () => this.onRecaptchaApiReady(), 350 );
		}
	}
}
