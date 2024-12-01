export class SelectAfterContainer extends $e.modules.hookUI.After {
	getCommand() {
		return 'document/elements/select';
	}

	getId() {
		return 'ehp-prevent-container-selection';
	}

	getConditions( args ) {
		let type = args?.container?.document?.config?.type;
		if ( ! type ) {
			type = args?.containers[ 0 ]?.document?.config?.type;
		}

		return [ 'ehp-header', 'ehp-footer' ].includes( type );
	}

	apply( args ) {
		const { container: { type = '' } } = args;
		switch ( type ) {
			case 'section':
			case 'container':
				$e.run( 'document/elements/select', { container: args.container.children[ 0 ], append: false } );
				break;
			default:
				break;
		}
	}
}

export default SelectAfterContainer;
