import Component from './component';
import FieldsMapControl from './fields-map-control';
import FieldsRepeaterControl from './fields-repeater-control';

export default class FormsModule extends elementorModules.editor.utils.Module {
	onElementorInit() {
		const ReplyToField = require( './reply-to-field' ),
			Recaptcha = require( './recaptcha' ),
			EmailDeliverability = require( './hints/email-deliverability' );

		this.replyToField = new ReplyToField();
		this.recaptcha = new Recaptcha( 'form' );

		// Form fields
		const TimeField = require( './fields/time' ),
			DateField = require( './fields/date' ),
			AcceptanceField = require( './fields/acceptance' ),
			TelField = require( './fields/tel' );

		this.Fields = {
			time: new TimeField( 'form' ),
			date: new DateField( 'form' ),
			tel: new TelField( 'form' ),
			acceptance: new AcceptanceField( 'form' ),
		};

		elementor.addControlView( 'Fields_map', FieldsMapControl );
		elementor.addControlView( 'form-fields-repeater', FieldsRepeaterControl );

		this.hints = {
			emailDeliverability: new EmailDeliverability(),
		};
	}

	onElementorInitComponents() {
		$e.components.register( new Component( { manager: this } ) );
	}
}
