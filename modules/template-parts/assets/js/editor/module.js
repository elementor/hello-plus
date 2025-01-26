import Component from './component';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import Dialog from './dialog';

export default class TemplatesModule extends elementorModules.editor.utils.Module {
	onElementorInit() {
		$e.components.register( new Component( { manager: this } ) );
		elementor.channels.editor.on( 'helloPlusLogo:change', this.openSiteIdentity );
		elementor.hooks.addFilter( 'elements/widget/controls/common/default', this.resetCommonControls.bind( this ) );
		elementor.hooks.addFilter( 'elements/widget/controls/common-optimized/default', this.resetCommonControls.bind( this ) );
		$e.routes.on( 'run:after', this.maybeShowDialog.bind( this ) );
		document.addEventListener( 'click', this.takeUserToHelloPlusWidgets );
		const types = [
			'core/modal/close/ehp-footer',
			'core/modal/close/ehp-header',
		];

		types.forEach( ( type ) => {
			window.addEventListener( type, this.redirectToHelloPlus );
		} );

		this.dialog = new Dialog();
		window.templatesModule = this;
	}

	redirectToHelloPlus() {
		window.location.href = elementor.config.close_modal_redirect_hello_plus;
	}

	async openSiteIdentity() {
		await $e.run( 'panel/global/open' );
		$e.route( 'panel/global/settings-site-identity' );
	}

	async maybeShowDialog( component, route ) {
		const howManyTimesOpened = parseInt( window.helloplusEditor.timesEditorOpened, 10 );

		if ( ! howManyTimesOpened ) {
			try {
				await apiFetch( {
					path: '/elementor-hello-plus/v1/set-editor-visited',
					method: 'POST',
				} );
			} catch ( error ) {
				console.error( error );
			}
			return;
		}

		if ( 'panel/elements/categories' === route && 1 === howManyTimesOpened ) {
			this.dialog.showDialog( {
				// eslint-disable-next-line @wordpress/valid-sprintf
				title: __( 'Building your website?', 'hello-plus' ),
				// eslint-disable-next-line @wordpress/valid-sprintf
				content: __( 'Did you know we have theme specific widgets that can take you there faster?', 'hello-plus' ),
				targetElement: document.querySelector( '#elementor-panel-category-layout' ),
				position: {
					blockStart: '-7',
				},
				actionButton: {
					url: '',
					text: __( 'Take Me There', 'hello-plus' ),
					classes: [ 'take-me-there', 'elementor-button', 'go-pro' ],
					callback: async () => {
						const parentElement = document.getElementById( 'elementor-panel-content-wrapper' );
						const targetElement = parentElement.querySelector( '#elementor-panel-category-helloplus' );

						if ( targetElement ) {
							const relativePosition = targetElement.offsetTop - parentElement.offsetTop;
							const container = document.querySelector( '#elementor-panel-content-wrapper' );
							container.scrollTop = relativePosition;

							try {
								await apiFetch( {
									path: '/elementor-hello-plus/v1/set-editor-visited',
									method: 'POST',
								} );
							} catch ( error ) {
								console.error( error );
							}
						}
					},
				},
			} );
		}
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
