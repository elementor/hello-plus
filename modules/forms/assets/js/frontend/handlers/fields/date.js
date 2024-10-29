import FieldBase from './data-time-field-base';

export default class DateField extends FieldBase {
	getFieldsSelector() {
		return '.elementor-date-field';
	}

	getPickerOptions( element ) {
		const $element = jQuery( element );

		return {
			minDate: $element.attr( 'min' ) || null,
			maxDate: $element.attr( 'max' ) || null,
			allowInput: true,
		};
	}
}
