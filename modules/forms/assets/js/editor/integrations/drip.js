var BaseIntegrationModule = require( './base' );

module.exports = BaseIntegrationModule.extend( {
	getName() {
		return 'drip';
	},

	onElementChange( setting ) {
		switch ( setting ) {
			case 'drip_api_token_source':
			case 'drip_custom_api_token':
				this.onApiUpdate();
				break;
			case 'drip_account':
				this.onDripAccountsUpdate();
				break;
		}
	},

	onApiUpdate() {
		var self = this,
			controlView = self.getEditorControlView( 'drip_api_token_source' ),
			customControlView = self.getEditorControlView( 'drip_custom_api_token' );

		if ( 'default' !== controlView.getControlValue() && '' === customControlView.getControlValue() ) {
			self.updateOptions( 'drip_account', [] );
			self.getEditorControlView( 'drip_account' ).setValue( '' );
			return;
		}

		self.addControlSpinner( 'drip_account' );

		this.getCacheKey( {
			type: 'accounts',
			controls: [
				controlView.getControlValue(),
				customControlView.getControlValue(),
			],
		} );

		self.getDripCache( 'accounts', 'accounts', controlView.getControlValue() ).done( function( data ) {
			self.updateOptions( 'drip_account', data.accounts );
		} );
	},

	onDripAccountsUpdate() {
		this.updateFieldsMapping();
	},

	updateFieldsMapping() {
		var controlView = this.getEditorControlView( 'drip_account' );

		if ( ! controlView.getControlValue() ) {
			return;
		}

		var remoteFields = {
			remote_label: __( 'Email', 'elementor' ),
			remote_type: 'email',
			remote_id: 'email',
			remote_required: true,
		};

		this.getEditorControlView( 'drip_fields_map' ).updateMap( [ remoteFields ] );
	},

	getDripCache( type, action, cacheKey, requestArgs ) {
		if ( _.has( this.cache[ type ], cacheKey ) ) {
			var data = {};
			data[ type ] = this.cache[ type ][ cacheKey ];
			return jQuery.Deferred().resolve( data );
		}

		requestArgs = _.extend( {}, requestArgs, {
			service: 'drip',
			drip_action: action,
			api_token: this.getEditorControlView( 'drip_api_token_source' ).getControlValue(),
			custom_api_token: this.getEditorControlView( 'drip_custom_api_token' ).getControlValue(),
		} );

		return this.fetchCache( type, cacheKey, requestArgs );
	},
} );
