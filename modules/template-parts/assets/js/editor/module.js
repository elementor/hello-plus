import Component from './component';

export default class TemplatesModule extends elementorModules.editor.utils.Module {
	onElementorInit() {
		$e.components.register( new Component( { manager: this } ) );
		elementor.channels.editor.on( 'helloPlusLogo:change', this.openSiteIdentity );
		elementor.hooks.addFilter( 'elements/widget/controls/common/default', this.resetCommonControls.bind( this ) );
		elementor.hooks.addFilter( 'elements/widget/controls/common-optimized/default', this.resetCommonControls.bind( this ) );
		elementor.hooks.addFilter( 'templates/source/is-remote', this.setSourceAsRemote.bind( this ) );

		const types = [
			'core/modal/close/ehp-footer',
			'core/modal/close/ehp-header',
		];

		types.forEach( ( type ) => {
			window.addEventListener( type, this.redirectToHelloPlus );
		} );

		window.templatesModule = this;
	}

	setSourceAsRemote( isRemote, activeSource ) {
		if ( 'remote-ehp' === activeSource ) {
			return true;
		}

		return isRemote;
	}

	redirectToHelloPlus() {
		window.location.href = elementor.config.close_modal_redirect_hello_plus;
	}

	async openSiteIdentity() {
		await $e.run( 'panel/global/open' );
		$e.route( 'panel/global/settings-site-identity' );
	}

	resetCommonControls( commonControls, widgetType ) {
		if ( [ 'ehp-footer', 'ehp-header' ].includes( widgetType ) ) {
			return null;
		}

		return commonControls;
	}

	isEhpDocument() {
		return [ 'ehp-footer', 'ehp-header' ].includes( elementor.config.document.type );
	}
}
