export default class helloPlusLogo {
	constructor() {
		elementor.channels.editor.on( 'helloPlusLogo:change', this.openSiteIdentity );
		elementor.channels.editor.on( 'section:activated', this.hideAdvancedTab.bind( this ) );
	}

	async openSiteIdentity() {
		await $e.run( 'panel/global/open' );
		$e.route( 'panel/global/settings-site-identity' );
	}

	hideAdvancedTab( sectionName, editor ) {
		const widgetType = editor?.model?.get( 'widgetType' ) || '';

		if ( widgetType.startsWith( 'ehp-' ) ) {
			return;
		}

		const advancedTab = editor?.el.querySelector( '.elementor-tab-control-advanced' ) || false;

		if ( advancedTab ) {
			advancedTab.style.display = 'none';
		}
	}
}

const HelloPlusLogoClass = new helloPlusLogo();
window.helloPlusLogo = HelloPlusLogoClass;
