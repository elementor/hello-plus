export default class helloPlusLogo {
	constructor() {
		elementor.channels.editor.on( 'helloPlusLogo:change', this.openSiteIdentity );
	}

	async openSiteIdentity() {
		await $e.run( 'panel/global/open' );
		$e.route( 'panel/global/settings-site-identity' );
	}
}

new helloPlusLogo();
