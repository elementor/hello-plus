module.exports = function() {
	var ApiValidations = require( './admin/api-validations' );

	this.dripButton = new ApiValidations( 'drip_api_token' );
	this.getResponse = new ApiValidations( 'getresponse_api_key' );
	this.convertKit = new ApiValidations( 'convertkit_api_key' );
	this.mailChimp = new ApiValidations( 'mailchimp_api_key' );
	this.mailerLite = new ApiValidations( 'mailerlite_api_key' );
	this.activeCcampaign = new ApiValidations( 'activecampaign_api_key', 'activecampaign_api_url' );

	jQuery( '.e-notice--cta.e-notice--dismissible[data-notice_id="site_mailer_forms_submissions_notice"] a.e-button--cta' ).on( 'click', function() {
		elementorCommon.ajax.addRequest( 'elementor_site_mailer_campaign', {
			data: {
				source: 'sm-submission-install',
			},
		} );
	} );
};
