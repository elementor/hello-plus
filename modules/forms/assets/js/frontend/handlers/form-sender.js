export default elementorModules.frontend.handlers.Base.extend( {

	getDefaultSettings() {
		return {
			selectors: {
				form: '.elementor-form',
				submitButton: '[type="submit"]',
			},
			action: 'elementor_pro_forms_send_form',
			ajaxUrl: elementorProFrontend.config.ajaxurl,
		};
	},

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' ),
			elements = {};

		elements.$form = this.$element.find( selectors.form );
		elements.$submitButton = elements.$form.find( selectors.submitButton );

		return elements;
	},

	bindEvents() {
		this.elements.$form.on( 'submit', this.handleSubmit );
		const $fileInput = this.elements.$form.find( 'input[type=file]' );
		if ( $fileInput.length ) {
			$fileInput.on( 'change', this.validateFileSize );
		}
	},

	validateFileSize( event ) {
		const $field = jQuery( event.currentTarget ),
			files = $field[ 0 ].files;

		if ( ! files.length ) {
			return;
		}

		const maxSize = parseInt( $field.attr( 'data-maxsize' ) ) * 1024 * 1024,
			maxSizeMessage = $field.attr( 'data-maxsize-message' );

		const filesArray = Array.prototype.slice.call( files );
		filesArray.forEach( ( file ) => {
			if ( maxSize < file.size ) {
				$field.parent()
					.addClass( 'elementor-error' )
					.append( '<span class="elementor-message elementor-message-danger elementor-help-inline elementor-form-help-inline" role="alert">' + maxSizeMessage + '</span>' )
					.find( ':input' ).attr( 'aria-invalid', 'true' );

				this.elements.$form.trigger( 'error' );
			}
		} );
	},

	beforeSend() {
		const $form = this.elements.$form;

		$form
			.animate( {
				opacity: '0.45',
			}, 500 )
			.addClass( 'elementor-form-waiting' );

		$form
			.find( '.elementor-message' )
			.remove();

		$form
			.find( '.elementor-error' )
			.removeClass( 'elementor-error' );

		$form
			.find( 'div.elementor-field-group' )
			.removeClass( 'error' )
			.find( 'span.elementor-form-help-inline' )
			.remove()
			.end()
			.find( ':input' ).attr( 'aria-invalid', 'false' );

		this.elements.$submitButton
			.attr( 'disabled', 'disabled' )
			.find( '> span' )
			.prepend( '<span class="elementor-button-text elementor-form-spinner"><i class="fa fa-spinner fa-spin"></i>&nbsp;</span>' );
	},

	getFormData() {
		const formData = new FormData( this.elements.$form[ 0 ] );
		formData.append( 'action', this.getSettings( 'action' ) );
		formData.append( 'referrer', location.toString() );

		return formData;
	},

	onSuccess( response ) {
		const $form = this.elements.$form;

		this.elements.$submitButton
			.removeAttr( 'disabled' )
			.find( '.elementor-form-spinner' )
			.remove();

		$form
			.animate( {
				opacity: '1',
			}, 100 )
			.removeClass( 'elementor-form-waiting' );

		if ( ! response.success ) {
			if ( response.data.errors ) {
				jQuery.each( response.data.errors, function( key, title ) {
					$form
						.find( '#form-field-' + key )
						.parent()
						.addClass( 'elementor-error' )
						.append( '<span class="elementor-message elementor-message-danger elementor-help-inline elementor-form-help-inline" role="alert">' + title + '</span>' )
						.find( ':input' ).attr( 'aria-invalid', 'true' );
				} );

				$form.trigger( 'error' );
			}
			$form.append( '<div class="elementor-message elementor-message-danger" role="alert">' + response.data.message + '</div>' );
		} else {
			$form.trigger( 'submit_success', response.data );

			// For actions like redirect page
			$form.trigger( 'form_destruct', response.data );

			$form.trigger( 'reset' );

			let successClass = 'elementor-message elementor-message-success';

			if ( elementorFrontendConfig.experimentalFeatures.e_font_icon_svg ) {
				successClass += ' elementor-message-svg';
			}

			if ( 'undefined' !== typeof response.data.message && '' !== response.data.message ) {
				$form.append( '<div class="' + successClass + '" role="alert">' + response.data.message + '</div>' );
			}
		}
	},

	onError( xhr, desc ) {
		const $form = this.elements.$form;

		$form.append( '<div class="elementor-message elementor-message-danger" role="alert">' + desc + '</div>' );

		this.elements.$submitButton
			.html( this.elements.$submitButton.text() )
			.removeAttr( 'disabled' );

		$form
			.animate( {
				opacity: '1',
			}, 100 )
			.removeClass( 'elementor-form-waiting' );

		$form.trigger( 'error' );
	},

	handleSubmit( event ) {
		const self = this,
			$form = this.elements.$form;

		event.preventDefault();

		if ( $form.hasClass( 'elementor-form-waiting' ) ) {
			return false;
		}

		this.beforeSend();

		jQuery.ajax( {
			url: self.getSettings( 'ajaxUrl' ),
			type: 'POST',
			dataType: 'json',
			data: self.getFormData(),
			processData: false,
			contentType: false,
			success: self.onSuccess,
			error: self.onError,
		} );
	},
} );
