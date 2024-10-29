var BaseIntegrationModule = require( './base' );

module.exports = BaseIntegrationModule.extend( {
	getName() {
		return 'getresponse';
	},

	onElementChange( setting ) {
		switch ( setting ) {
			case 'getresponse_custom_api_key':
			case 'getresponse_api_key_source':
				this.onApiUpdate();
				break;
			case 'getresponse_list':
				this.onGetResonseListUpdate();
				break;
		}
	},

	onApiUpdate() {
		var self = this,
			controlView = self.getEditorControlView( 'getresponse_api_key_source' ),
			customControlView = self.getEditorControlView( 'getresponse_custom_api_key' );

		if ( 'default' !== controlView.getControlValue() && '' === customControlView.getControlValue() ) {
			self.updateOptions( 'getresponse_list', [] );
			self.getEditorControlView( 'getresponse_list' ).setValue( '' );
			return;
		}

		self.addControlSpinner( 'getresponse_list' );

		const cacheKey = this.getCacheKey( {
			type: 'lists',
			controls: [
				controlView.getControlValue(),
				customControlView.getControlValue(),
			],
		} );

		self.getCache( 'lists', 'lists', cacheKey ).done( function( data ) {
			self.updateOptions( 'getresponse_list', data.lists );
		} );
	},

	onGetResonseListUpdate() {
		this.updatGetResonseList();
	},

	updatGetResonseList() {
		var self = this,
			controlView = self.getEditorControlView( 'getresponse_list' );

		if ( ! controlView.getControlValue() ) {
			return;
		}

		self.addControlSpinner( 'getresponse_fields_map' );
		const cacheKey = this.getCacheKey( {
			type: 'fields',
			controls: [
				controlView.getControlValue(),
			],
		} );

		self.getCache( 'fields', 'get_fields', cacheKey, {
			getresponse_list: controlView.getControlValue(),
		} ).done( function( data ) {
			self.getEditorControlView( 'getresponse_fields_map' ).updateMap( data.fields );
		} );
	},

	getCache( type, action, cacheKey, requestArgs ) {
		if ( _.has( this.cache[ type ], cacheKey ) ) {
			var data = {};
			data[ type ] = this.cache[ type ][ cacheKey ];
			return jQuery.Deferred().resolve( data );
		}

		requestArgs = _.extend( {}, requestArgs, {
			service: 'getresponse',
			getresponse_action: action,
			api_key: this.getEditorControlView( 'getresponse_api_key_source' ).getControlValue(),
			custom_api_key: this.getEditorControlView( 'getresponse_custom_api_key' ).getControlValue(),
		} );

		return this.fetchCache( type, cacheKey, requestArgs );
	},

	onSectionActive() {
		BaseIntegrationModule.prototype.onSectionActive.apply( this, arguments );

		this.updatGetResonseList();
	},
} );
