import Component from './component';
import { __ } from '@wordpress/i18n';

export default class TemplatesModule extends elementorModules.editor.utils.Module {
	onElementorInit() {
		$e.components.register( new Component( { manager: this } ) );
		elementor.channels.editor.on( 'helloPlusLogo:change', this.openSiteIdentity );
		elementor.hooks.addFilter( 'elements/widget/controls/common/default', this.resetCommonControls.bind( this ) );
		elementor.hooks.addFilter( 'elements/widget/controls/common-optimized/default', this.resetCommonControls.bind( this ) );
		$e.routes.on( 'run:after', this.maybeShowDialog.bind( this ) );
		document.addEventListener( 'click', this.takeUserToHelloPlusWidgets );
		window.templatesModule = this;
	}

	takeUserToHelloPlusWidgets( event ) {
		console.log( 'click event', event );
		if ( event.target.matches( '.take-me-there' ) ) {
			event.preventDefault();
			// Your event handler code here
			console.log( 'Button with class .take-me-there clicked' );
		}
	}

	async openSiteIdentity() {
		await $e.run( 'panel/global/open' );
		$e.route( 'panel/global/settings-site-identity' );
	}

	maybeShowDialog( component, route ) {
		if ( 'panel/elements/categories' === route ) {
			elementor.promotion.showDialog( {
				// eslint-disable-next-line @wordpress/valid-sprintf
				title: __( 'Building your website?', 'hello-plus' ),
				// eslint-disable-next-line @wordpress/valid-sprintf
				content: __( 'Did you know we have theme specific widgets that can take you there faster?', 'hello-plus' ),
				targetElement: document.querySelector( '#elementor-panel-category-layout' ),
				position: {
					blockStart: '-7',
				},
				actionButton: {
					// eslint-disable-next-line @wordpress/valid-sprintf
					url: null,
					text: __( 'Take Me There', 'hello-plus' ),
					classes: [ 'take-me-there' ],
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
}
