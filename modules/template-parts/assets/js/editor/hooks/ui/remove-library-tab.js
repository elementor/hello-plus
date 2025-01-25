export class EhpRemoveLibraryTab extends $e.modules.hookUI.After {
	getCommand() {
		return 'editor/documents/unload';
	}

	getId() {
		return 'ehp-remove-library-tab';
	}

	getConditions( args ) {
		const document = elementor.documents.get( args.id );
		return [ 'ehp-header', 'ehp-footer' ].includes( document.config.type );
	}

	apply() {
		$e.components.get( 'library' ).removeTab( 'templates/ehp-elements' );

		$e.components.get( 'library' ).addTab( 'templates/pages' );
		$e.components.get( 'library' ).addTab( 'templates/blocks' );
	}
}

export default EhpRemoveLibraryTab;
