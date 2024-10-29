export default class DataTimeFieldBase extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				fields: this.getFieldsSelector(),
			},
			classes: {
				useNative: 'elementor-use-native',
			},
		};
	}

	getDefaultElements() {
		const { selectors } = this.getDefaultSettings();

		return {
			$fields: this.$element.find( selectors.fields ),
		};
	}

	addPicker( element ) {
		const { classes } = this.getDefaultSettings(),
			$element = jQuery( element );

		if ( $element.hasClass( classes.useNative ) ) {
			return;
		}

		element.flatpickr( this.getPickerOptions( element ) );
	}

	onInit( ...args ) {
		super.onInit( ...args );

		this.elements.$fields.each( ( index, element ) => this.addPicker( element ) );
	}
}
