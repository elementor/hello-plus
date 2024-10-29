var BaseIntegrationModule = require( './base' );

module.exports = BaseIntegrationModule.extend( {
	getName() {
		return 'mailchimp';
	},

	onElementChange( setting ) {
		switch ( setting ) {
			case 'mailchimp_api_key_source':
			case 'mailchimp_api_key':
				this.onApiUpdate();
				break;
			case 'mailchimp_list':
				this.onMailchimpListUpdate();
				break;
		}
	},

	onApiUpdate() {
		var self = this,
			controlView = self.getEditorControlView( 'mailchimp_api_key' ),
			GlobalApiKeycontrolView = self.getEditorControlView( 'mailchimp_api_key_source' );

		if ( 'default' !== GlobalApiKeycontrolView.getControlValue() && '' === controlView.getControlValue() ) {
			self.updateOptions( 'mailchimp_list', [] );
			self.getEditorControlView( 'mailchimp_list' ).setValue( '' );
			return;
		}

		// Add a spinner to the `Audience` list control.
		self.resetControlIndicators( 'mailchimp_list' );
		self.addControlSpinner( 'mailchimp_list' );

		const cacheKey = this.getCacheKey( {
			type: 'lists',
			controls: [
				controlView.getControlValue(),
				GlobalApiKeycontrolView.getControlValue(),
			],
		} );

		// Fetch data
		self.getMailchimpCache( 'lists', 'lists', cacheKey ).done( function( data ) {
			self.updateOptions( 'mailchimp_list', data.lists );
			self.updatMailchimpList();
		} ).fail( function( error ) {
			self.addControlError( 'mailchimp_list', error );
		} ).always( function() {
			self.removeControlSpinner( 'mailchimp_list' );
		} );
	},

	onMailchimpListUpdate() {
		this.updateOptions( 'mailchimp_groups', [] );
		this.getEditorControlView( 'mailchimp_groups' ).setValue( '' );
		this.updatMailchimpList();
	},

	updatMailchimpList() {
		var self = this,
			controlView = self.getEditorControlView( 'mailchimp_list' );

		if ( ! controlView.getControlValue() ) {
			return;
		}

		// Add a spinner to the groups select box.
		self.resetControlIndicators( 'mailchimp_groups' );
		self.addControlSpinner( 'mailchimp_groups' );

		this.getCacheKey( {
			type: 'list_details',
			controls: [
				controlView.getControlValue(),
			],
		} );

		// Fetch The data
		self.getMailchimpCache( 'list_details', 'list_details', controlView.getControlValue(), {
			mailchimp_list: controlView.getControlValue(),
		} ).done( function( data ) {
			self.updateOptions( 'mailchimp_groups', data.list_details.groups );
			self.getEditorControlView( 'mailchimp_fields_map' ).updateMap( data.list_details.fields );
		} ).fail( function( error ) {
			self.addControlError( 'mailchimp_groups', error );
		} ).always( function() {
			self.removeControlSpinner( 'mailchimp_groups' );
		} );

		// Get list fields.
		// The requests needed to be executed immediately in order to fill the `Field Mapping` select-boxes
		// without waiting for other requests to finish.
		const args = {
			type: 'fields',
			action: 'fields',
			cacheKey: controlView.getControlValue(),
			args: { mailchimp_list: controlView.getControlValue() },
			immediately: true,
		};

		self.getMailchimpCache( ...Object.values( args ) ).done( function( data ) {
			self.getEditorControlView( 'mailchimp_fields_map' ).updateMap( data.fields );
		} );
	},

	getMailchimpCache( type, action, cacheKey, requestArgs, immediately = false ) {
		if ( _.has( this.cache[ type ], cacheKey ) ) {
			var data = {};
			data[ type ] = this.cache[ type ][ cacheKey ];
			return jQuery.Deferred().resolve( data );
		}

		requestArgs = _.extend( {}, requestArgs, {
			service: 'mailchimp',
			mailchimp_action: action,
			api_key: this.getEditorControlView( 'mailchimp_api_key' ).getControlValue(),
			use_global_api_key: this.getEditorControlView( 'mailchimp_api_key_source' ).getControlValue(),
		} );

		return this.fetchCache( type, cacheKey, requestArgs, immediately );
	},

	onSectionActive() {
		BaseIntegrationModule.prototype.onSectionActive.apply( this, arguments );

		this.onApiUpdate();
	},
} );
