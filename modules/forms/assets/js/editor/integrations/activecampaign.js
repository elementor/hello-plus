var BaseIntegrationModule = require( './base' );

module.exports = BaseIntegrationModule.extend( {
	fields: {},

	getName() {
		return 'activecampaign';
	},

	onElementChange( setting ) {
		switch ( setting ) {
			case 'activecampaign_api_credentials_source':
			case 'activecampaign_api_key':
			case 'activecampaign_api_url':
				this.onApiUpdate();
				break;
			case 'activecampaign_list':
				this.onListUpdate();
				break;
		}
	},

	onApiUpdate() {
		const self = this,
			apikeyControlView = self.getEditorControlView( 'activecampaign_api_key' ),
			apiUrlControlView = self.getEditorControlView( 'activecampaign_api_url' ),
			apiCredControlView = self.getEditorControlView( 'activecampaign_api_credentials_source' );

		if ( 'default' !== apiCredControlView.getControlValue() && ( '' === apikeyControlView.getControlValue() || '' === apiUrlControlView.getControlValue() ) ) {
			self.updateOptions( 'activecampaign_list', [] );
			self.getEditorControlView( 'activecampaign_list' ).setValue( '' );
			return;
		}

		self.addControlSpinner( 'activecampaign_list' );

		const cacheKey = this.getCacheKey( {
			controls: [
				apiCredControlView.getControlValue(),
				apiUrlControlView.getControlValue(),
				apikeyControlView.getControlValue(),
			],
		} );
		self.getActiveCampaignCache( 'lists', 'activecampaign_list', cacheKey ).done( function( data ) {
			self.updateOptions( 'activecampaign_list', data.lists );
			self.fields = data.fields;
		} );
	},

	onListUpdate() {
		this.updateFieldsMapping();
	},

	updateFieldsMapping() {
		var controlView = this.getEditorControlView( 'activecampaign_list' );

		if ( ! controlView.getControlValue() ) {
			return;
		}

		var remoteFields = [
			{
				remote_label: __( 'Email', 'elementor' ),
				remote_type: 'email',
				remote_id: 'email',
				remote_required: true,
			},
			{
				remote_label: __( 'First Name', 'elementor' ),
				remote_type: 'text',
				remote_id: 'first_name',
				remote_required: false,
			},
			{
				remote_label: __( 'Last Name', 'elementor' ),
				remote_type: 'text',
				remote_id: 'last_name',
				remote_required: false,
			},
			{
				remote_label: __( 'Phone', 'elementor' ),
				remote_type: 'text',
				remote_id: 'phone',
				remote_required: false,
			},
			{
				remote_label: __( 'Organization name', 'elementor' ),
				remote_type: 'text',
				remote_id: 'orgname',
				remote_required: false,
			},
		];

		for ( var field in this.fields ) {
			if ( Object.prototype.hasOwnProperty.call( this.fields, field ) ) {
				remoteFields.push( this.fields[ field ] );
			}
		}

		this.getEditorControlView( 'activecampaign_fields_map' ).updateMap( remoteFields );
	},

	getActiveCampaignCache( type, action, cacheKey, requestArgs ) {
		if ( _.has( this.cache[ type ], cacheKey ) ) {
			var data = {};
			data[ type ] = this.cache[ type ][ cacheKey ];
			return jQuery.Deferred().resolve( data );
		}

		requestArgs = _.extend( {}, requestArgs, {
			service: 'activecampaign',
			activecampaign_action: action,
			api_key: this.getEditorControlView( 'activecampaign_api_key' ).getControlValue(),
			api_url: this.getEditorControlView( 'activecampaign_api_url' ).getControlValue(),
			api_cred: this.getEditorControlView( 'activecampaign_api_credentials_source' ).getControlValue(),
		} );

		return this.fetchCache( type, cacheKey, requestArgs );
	},
} );
