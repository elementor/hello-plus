import Component from './component';

export default class FormsModule extends elementorModules.editor.utils.Module {
	onElementorInit() {
		const ReplyToField = require( './reply-to-field' ),
			Recaptcha = require( './recaptcha' ),
			MailerLite = require( './integrations/mailerlite' ),
			Mailchimp = require( './integrations/mailchimp' ),
			Drip = require( './integrations/drip' ),
			ActiveCampaign = require( './integrations/activecampaign' ),
			GetResponse = require( './integrations/getresponse' ),
			ConvertKit = require( './integrations/convertkit' ),
			EmailDeliverability = require( './hints/email-deliverability' );

		this.replyToField = new ReplyToField();
		this.mailchimp = new Mailchimp( 'form' );
		this.recaptcha = new Recaptcha( 'form' );
		this.drip = new Drip( 'form' );
		this.activecampaign = new ActiveCampaign( 'form' );
		this.getresponse = new GetResponse( 'form' );
		this.convertkit = new ConvertKit( 'form' );
		this.mailerlite = new MailerLite( 'form' );

		// Form fields
		const TimeField = require( './fields/time' ),
			DateField = require( './fields/date' ),
			AcceptanceField = require( './fields/acceptance' ),
			UploadField = require( './fields/upload' ),
			TelField = require( './fields/tel' );

		this.Fields = {
			time: new TimeField( 'form' ),
			date: new DateField( 'form' ),
			tel: new TelField( 'form' ),
			acceptance: new AcceptanceField( 'form' ),
			upload: new UploadField( 'form' ),
		};

		elementor.addControlView( 'Fields_map', require( './fields-map-control' ) );
		elementor.addControlView( 'form-fields-repeater', require( './fields-repeater-control' ) );

		this.hints = {
			emailDeliverability: new EmailDeliverability(),
		};
	}

	onElementorInitComponents() {
		$e.components.register( new Component( { manager: this } ) );
	}
}
