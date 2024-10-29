module.exports = elementorModules.editor.utils.Module.extend( {
	enqueueRecaptchaJs( url, type ) {
		if ( ! elementorFrontend.elements.$body.find( '[src="' + url + '"]' ).length ) {
			elementorFrontend.elements.$body.append( '<scr' + 'ipt src="' + url + '" id="recaptcha-' + type + '"</scri' + 'pt>' );
		}
	},

	renderField( inputField, item ) {
		inputField += '<div class="elementor-field ' + item.field_type + ' ">';
		inputField += this.getDataSettings( item );
		inputField += '</div>';

		return inputField;
	},

	getDataSettings( item ) {
		const config = elementorPro.config.forms[ item.field_type ],
			srcURL = 'https://www.google.com/recaptcha/api.js?render=explicit';

		if ( ! config.enabled ) {
			return '<div class="elementor-alert elementor-alert-info">' + config.setup_message + '</div>';
		}

		let recaptchaData = 'data-sitekey="' + config.site_key + '" data-type="' + config.type + '"';

		switch ( config.type ) {
			case 'v3' :
				recaptchaData += ' data-action="form" data-size="invisible" data-badge="' + item.recaptcha_badge + '"';
				break;
			case 'v2_checkbox':
				recaptchaData += ' data-theme="' + item.recaptcha_style + '"';
				recaptchaData += ' data-size="' + item.recaptcha_size + '"';
				break;
		}
		this.enqueueRecaptchaJs( srcURL, config.type );

		return '<div class="elementor-g-recaptcha' + _.escape( item.css_classes ) + '" ' + recaptchaData + '></div>';
	},

	filterItem( item ) {
		if ( 'recaptcha' === item.field_type ) {
			item.field_label = false;
		}

		return item;
	},

	onInit() {
		elementor.hooks.addFilter( 'elementor_pro/forms/content_template/item', this.filterItem );
		elementor.hooks.addFilter( 'elementor_pro/forms/content_template/field/recaptcha', this.renderField, 10, 2 );
		elementor.hooks.addFilter( 'elementor_pro/forms/content_template/field/recaptcha_v3', this.renderField, 10, 2 );
	},
} );
