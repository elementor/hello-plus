var BaseIntegrationModule = require( './base' );

module.exports = BaseIntegrationModule.extend( {

	getName() {
		return 'convertkit';
	},

	onElementChange( setting ) {
		switch ( setting ) {
			case 'convertkit_api_key_source':
			case 'convertkit_custom_api_key':
				this.onApiUpdate();
				break;
			case 'convertkit_form':
				this.onListUpdate();
				break;
		}
	},

	onApiUpdate() {
		var self = this,
			apiKeyControlView = self.getEditorControlView( 'convertkit_api_key_source' ),
			customApikeyControlView = self.getEditorControlView( 'convertkit_custom_api_key' );

		if ( 'default' !== apiKeyControlView.getControlValue() && '' === customApikeyControlView.getControlValue() ) {
			self.updateOptions( 'convertkit_form', [] );
			self.getEditorControlView( 'convertkit_form' ).setValue( '' );
			return;
		}

		self.addControlSpinner( 'convertkit_form' );
		const cacheKey = this.getCacheKey( {
			type: 'data',
			controls: [
				apiKeyControlView.getControlValue(),
				customApikeyControlView.getControlValue(),
			],
		} );

		self.getConvertKitCache( 'data', 'convertkit_get_forms', cacheKey ).done( function( data ) {
			self.updateOptions( 'convertkit_form', data.data.forms );
			self.updateOptions( 'convertkit_tags', data.data.tags );
		} );
	},

	onListUpdate() {
		this.updateFieldsMapping();
	},

	updateFieldsMapping() {
		var controlView = this.getEditorControlView( 'convertkit_form' );

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
		];

		this.getEditorControlView( 'convertkit_fields_map' ).updateMap( remoteFields );
	},

	getConvertKitCache( type, action, cacheKey, requestArgs ) {
		if ( _.has( this.cache[ type ], cacheKey ) ) {
			var data = {};
			data[ type ] = this.cache[ type ][ cacheKey ];
			return jQuery.Deferred().resolve( data );
		}

		requestArgs = _.extend( {}, requestArgs, {
			service: 'convertkit',
			convertkit_action: action,
			api_key: this.getEditorControlView( 'convertkit_api_key_source' ).getControlValue(),
			custom_api_key: this.getEditorControlView( 'convertkit_custom_api_key' ).getControlValue(),
		} );

		return this.fetchCache( type, cacheKey, requestArgs );
	},
} );
