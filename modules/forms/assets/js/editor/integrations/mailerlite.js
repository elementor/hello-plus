const BaseIntegrationModule = require( './base' );

module.exports = BaseIntegrationModule.extend( {
	fields: {},

	getName() {
		return 'mailerlite';
	},

	onElementChange( setting ) {
		switch ( setting ) {
			case 'mailerlite_api_key_source':
			case 'mailerlite_custom_api_key':
				this.onMailerliteApiKeyUpdate();
				break;
			case 'mailerlite_group':
				this.updateFieldsMapping();
				break;
		}
	},

	onMailerliteApiKeyUpdate() {
		var self = this,
			controlView = self.getEditorControlView( 'mailerlite_custom_api_key' ),
			GlobalApiKeycontrolView = self.getEditorControlView( 'mailerlite_api_key_source' );

		if ( 'default' !== GlobalApiKeycontrolView.getControlValue() && '' === controlView.getControlValue() ) {
			self.updateOptions( 'mailerlite_group', [] );
			self.getEditorControlView( 'mailerlite_group' ).setValue( '' );
			return;
		}

		self.addControlSpinner( 'mailerlite_group' );

		const cacheKey = this.getCacheKey( {
			type: 'groups',
			controls: [
				controlView.getControlValue(),
				GlobalApiKeycontrolView.getControlValue(),
			],
		} );

		self.getMailerliteCache( 'groups', 'groups', cacheKey ).done( function( data ) {
			self.updateOptions( 'mailerlite_group', data.groups );
			self.fields = data.fields;
		} );
	},

	updateFieldsMapping() {
		const controlView = this.getEditorControlView( 'mailerlite_group' );

		if ( ! controlView.getControlValue() ) {
			return;
		}

		const remoteFields = [
			{
				remote_label: __( 'Email', 'elementor' ),
				remote_type: 'email',
				remote_id: 'email',
				remote_required: true,
			},
			{
				remote_label: __( 'Name', 'elementor' ),
				remote_type: 'text',
				remote_id: 'name',
				remote_required: false,
			},
			{
				remote_label: __( 'Last Name', 'elementor' ),
				remote_type: 'text',
				remote_id: 'last_name',
				remote_required: false,
			},
			{
				remote_label: __( 'Company', 'elementor' ),
				remote_type: 'text',
				remote_id: 'company',
				remote_required: false,
			},
			{
				remote_label: __( 'Phone', 'elementor' ),
				remote_type: 'text',
				remote_id: 'phone',
				remote_required: false,
			},
			{
				remote_label: __( 'Country', 'elementor' ),
				remote_type: 'text',
				remote_id: 'country',
				remote_required: false,
			},
			{
				remote_label: __( 'State', 'elementor' ),
				remote_type: 'text',
				remote_id: 'state',
				remote_required: false,
			},
			{
				remote_label: __( 'City', 'elementor' ),
				remote_type: 'text',
				remote_id: 'city',
				remote_required: false,
			},
			{
				remote_label: __( 'Zip', 'elementor' ),
				remote_type: 'text',
				remote_id: 'zip',
				remote_required: false,
			},
		];

		for ( const field in this.fields ) {
			if ( Object.prototype.hasOwnProperty.call( this.fields, field ) ) {
				remoteFields.push( this.fields[ field ] );
			}
		}

		this.getEditorControlView( 'mailerlite_fields_map' ).updateMap( remoteFields );
	},

	getMailerliteCache( type, action, cacheKey, requestArgs ) {
		if ( _.has( this.cache[ type ], cacheKey ) ) {
			const data = {};
			data[ type ] = this.cache[ type ][ cacheKey ];
			return jQuery.Deferred().resolve( data );
		}

		requestArgs = _.extend( {}, requestArgs, {
			service: 'mailerlite',
			mailerlite_action: action,
			custom_api_key: this.getEditorControlView( 'mailerlite_custom_api_key' ).getControlValue(),
			api_key: this.getEditorControlView( 'mailerlite_api_key_source' ).getControlValue(),
		} );

		return this.fetchCache( type, cacheKey, requestArgs );
	},

	onSectionActive() {
		BaseIntegrationModule.prototype.onSectionActive.apply( this, arguments );

		this.onMailerliteApiKeyUpdate();
	},

} );
