var ElementEditorModule = require( 'elementor-pro/editor/element-editor-module' );

module.exports = ElementEditorModule.extend( {

	__construct() {
		this.cache = {};
		ElementEditorModule.prototype.__construct.apply( this, arguments );
	},

	getName() {
		return '';
	},

	getCacheKey( args ) {
		return JSON.stringify( {
			service: this.getName(),
			data: args,
		} );
	},

	fetchCache( type, cacheKey, requestArgs, immediately = false ) {
		return elementorPro.ajax.addRequest( 'forms_panel_action_data', {
			unique_id: 'integrations_' + this.getName(),
			data: requestArgs,
			success: ( data ) => {
				this.cache[ type ] = _.extend( {}, this.cache[ type ] );
				this.cache[ type ][ cacheKey ] = data[ type ];
			},
		}, immediately );
	},

	onInit() {
		this.addSectionListener( 'section_' + this.getName(), this.onSectionActive );
	},

	onSectionActive() {
		this.onApiUpdate();
	},

	onApiUpdate() {},
} );
