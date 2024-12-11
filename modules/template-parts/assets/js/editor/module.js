import Component from './component';

export default class TemplatesModule extends elementorModules.editor.utils.Module {
	onElementorInit() {
		$e.components.register( new Component( { manager: this } ) );
		elementor.channels.editor.on( 'helloPlusLogo:change', this.openSiteIdentity );
		elementor.hooks.addFilter( 'elements/widget/controls/common/default', this.resetCommonControls.bind( this ) );
		elementor.hooks.addFilter( 'elements/widget/controls/common-optimized/default', this.resetCommonControls.bind( this ) );

		window.templatesModule = this;
	}

	async openSiteIdentity() {
		await $e.run( 'panel/global/open' );
		$e.route( 'panel/global/settings-site-identity' );
	}

	resetCommonControls( commonControls, widgetType ) {
		if ( [ 'ehp-footer', 'ehp-header' ].includes(widgetType) ) {
			return null;
		}

		return commonControls;
	}
}
