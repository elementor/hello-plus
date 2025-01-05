import { __ } from '@wordpress/i18n';

export class EhpAddLibraryTab extends $e.modules.hookUI.Before {
	getCommand() {
		return 'library/open';
	}

	getId() {
		return 'ehp-add-library-tab';
	}

	getConditions() {
		return [ 'ehp-header', 'ehp-footer' ].includes( elementor?.config?.document?.type );
	}

	getTitle() {
		switch ( elementor?.config?.document?.type ) {
			case 'ehp-header':
				return __( 'Hello+ Header', 'elementor' );
			case 'ehp-footer':
				return __( 'Hello+ Footer', 'elementor' );
			default:
				return __( 'Hello Plus', 'elementor' );
		}
	}

	apply() {
		$e.components.get( 'library' ).addTab( 'templates/ehp-elements', {
			title: this.getTitle(),
			filter: {
				source: 'remote',
				type: 'floating_button',
			},
		}, 2 );

		$e.components.get( 'library' ).removeTab( 'templates/blocks' );
		$e.components.get( 'library' ).removeTab( 'templates/pages' );
	}
}

export default EhpAddLibraryTab;
