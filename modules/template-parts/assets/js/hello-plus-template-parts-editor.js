export default class helloPlusLogo {
	constructor() {
		elementor.channels.editor.on( 'helloPlusLogo:change', this.openSiteIdentity );
		elementor.hooks.addFilter( 'elements/widget/controls/common/default', this.resetCommonControls.bind( this ) );
		elementor.hooks.addFilter( 'elements/widget/controls/common-optimized/default', this.resetCommonControls.bind( this ) );
	}

	async openSiteIdentity() {
		await $e.run( 'panel/global/open' );
		$e.route( 'panel/global/settings-site-identity' );
	}

	resetCommonControls( commonControls, widgetType ) {
		if ( widgetType.startsWith( 'ehp-' ) ) {
			return null;
		}

		return commonControls;
	}
}

const HelloPlusLogoClass = new helloPlusLogo();
window.helloPlusLogo = HelloPlusLogoClass;
