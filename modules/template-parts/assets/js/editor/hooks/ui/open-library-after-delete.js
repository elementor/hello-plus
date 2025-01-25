export class EhpOpenLibraryAfterDelete extends $e.modules.hookUI.After {
	getCommand() {
		return 'document/elements/delete';
	}

	getId() {
		return 'ehp-open-library-after-delete';
	}

	getConditions( args ) {
		let type = args?.container?.document?.config?.type;
		if ( ! type ) {
			type = args?.containers[ 0 ]?.document?.config?.type;
		}

		return [ 'ehp-header', 'ehp-footer' ].includes( type );
	}

	apply() {
		$e.run( 'library/open' );
	}
}

export default EhpOpenLibraryAfterDelete;
