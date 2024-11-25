export class SelectTemplatePartsOnOpen {
	constructor() {
		this.command = 'editor/documents/attach-preview';
	}

	getConditions() {
		return 'ehp-header' === elementor?.config?.document?.type;
	}

	apply() {
		const children = elementor?.documents?.currentDocument?.container?.children;

		if ( Array.isArray( children ) && children.length ) {
			$e.run(
				'document/elements/select',
				{ container: children[ 0 ], append: false },
			);
		} else {
			$e.run( 'library/open' );
		}
	}
}

export default SelectTemplatePartsOnOpen;
