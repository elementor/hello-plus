import FieldBase from './data-time-field-base';

export default class TimeField extends FieldBase {
	getFieldsSelector() {
		return '.elementor-time-field';
	}

	getPickerOptions() {
		return {
			noCalendar: true,
			enableTime: true,
			allowInput: true,
		};
	}
}
