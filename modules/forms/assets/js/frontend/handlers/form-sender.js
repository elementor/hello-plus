export default elementorModules.frontend.handlers.Base.extend( {
	getDefaultSettings() {
		return {
			selectors: {
				form: '.ehp-form',
				submitButton: '[type="submit"]',
			},
			action: 'helloplus_forms_lite_send_form',
			ajaxUrl: elementorFrontendConfig.urls.ajaxurl,
			nonce: ehpFormsData.nonce,
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
		this.elements.$form.addEventListener( 'submit', this.handleSubmit.bind( this ) );
	},

    beforeSend() {
        const form = this.elements.$form;

        form.style.opacity = '0.45';
        form.classList.add( 'elementor-form-waiting' );

        const messageElement = form.querySelector( '.elementor-message' );
        if ( messageElement ) {
			messageElement.remove();
		}

        const errorElement = form.querySelector( '.elementor-error' );
        if ( errorElement ) {
			errorElement.classList.remove( 'elementor-error' );
		}

        const fieldGroups = form.querySelectorAll( 'div.elementor-field-group' );
        fieldGroups.forEach( ( group ) => {
            group.classList.remove( 'error' );
            const helpInline = group.querySelector( 'span.elementor-form-help-inline' );
            if ( helpInline ) {
				helpInline.remove();
			}

            const inputElements = group.querySelectorAll( ':input' );
            inputElements.forEach( ( input ) => input.setAttribute( 'aria-invalid', 'false' ) );
        } );

        this.elements.$submitButton.setAttribute( 'disabled', 'disabled' );
        const spinner = document.createElement( 'span' );
        spinner.classList.add( 'elementor-button-text', 'elementor-form-spinner' );
        spinner.innerHTML = '<i class="fa fa-spinner fa-spin"></i>&nbsp;';
        this.elements.$submitButton.prepend( spinner );
    },

    getFormData() {
        const formData = new FormData( this.elements.$form );
        formData.append( 'action', this.getSettings( 'action' ) );
        formData.append( 'nonce', this.getSettings( 'nonce' ) );
        formData.append( 'referrer', location.toString() );

        return formData;
    },

    onSuccess( response ) {
		const form = this.elements.$form;

		this.elements.$submitButton.removeAttribute( 'disabled' );
		const spinner = this.elements.$submitButton.querySelector( '.elementor-form-spinner' );
		if ( spinner ) {
			spinner.remove();
		}

		form.style.opacity = '1';
		form.classList.remove( 'elementor-form-waiting' );

		if ( ! response.success ) {
			if ( response.data.errors ) {
				Object.entries( response.data.errors ).forEach( ( [ key, title ] ) => {
					const field = form.querySelector( `#form-field-${ key }` );
					if ( field ) {
						field.parentElement.classList.add( 'elementor-error' );
						const errorMessage = document.createElement( 'span' );
						errorMessage.classList.add( 'elementor-message', 'elementor-message-danger', 'elementor-help-inline', 'elementor-form-help-inline' );
						errorMessage.setAttribute( 'role', 'alert' );
						errorMessage.textContent = title;
						field.parentElement.appendChild( errorMessage );
						const input = field.querySelector( 'input' );
						if ( input ) {
							input.setAttribute( 'aria-invalid', 'true' );
						}
					}
				} );
				form.dispatchEvent( new Event( 'error' ) );
			}

			const errorMessage = document.createElement( 'div' );
			errorMessage.classList.add( 'elementor-message', 'elementor-message-danger' );
			errorMessage.setAttribute( 'role', 'alert' );
			errorMessage.textContent = response.data.message;
			form.appendChild( errorMessage );
		} else {
			form.dispatchEvent( new CustomEvent( 'submit_success', { detail: response.data } ) );
			form.dispatchEvent( new CustomEvent( 'form_destruct', { detail: response.data } ) );

			form.reset();

			const successClass = [ 'elementor-message', 'elementor-message-success' ];
			if ( elementorFrontendConfig.experimentalFeatures.e_font_icon_svg ) {
				successClass.push( 'elementor-message-svg' );
			}

			if ( response.data.message && response.data.message !== '' ) {
				const successMessage = document.createElement( 'div' );
				successMessage.classList.add( ...successClass );
				successMessage.setAttribute( 'role', 'alert' );
				successMessage.textContent = response.data.message;
				form.appendChild( successMessage );
			}
		}
    },

    onError( xhr, desc ) {
        const form = this.elements.$form;

        const dangerMessage = document.createElement( 'div' );
        dangerMessage.classList.add( 'elementor-message', 'elementor-message-danger' );
        dangerMessage.setAttribute( 'role', 'alert' );
        dangerMessage.textContent = desc;
        form.appendChild( dangerMessage );

        this.elements.$submitButton.innerHTML = this.elements.$submitButton.textContent;
        this.elements.$submitButton.removeAttribute( 'disabled' );

        form.style.opacity = '1';
        form.classList.remove( 'elementor-form-waiting' );

        form.dispatchEvent( new Event( 'error' ) );
    },

    handleSubmit( event ) {
        event.preventDefault();

        const form = this.elements.$form;

        if ( form.classList.contains( 'elementor-form-waiting' ) ) {
            return false;
        }

        this.beforeSend();

        fetch( this.getSettings( 'ajaxUrl' ), {
            method: 'POST',
            body: this.getFormData(),
        } )
        .then( ( response ) => response.json() )
        .then( ( data ) => this.onSuccess( data ) )
        .catch( ( error ) => this.onError( null, error ) );
    },
} );
