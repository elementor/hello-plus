import Component from './component';

export default class TemplatesModule extends elementorModules.editor.utils.Module {
	onElementorInit() {
		$e.components.register( new Component( { manager: this } ) );
		elementor.channels.editor.on( 'helloPlusLogo:change', this.openSiteIdentity );
		elementor.hooks.addFilter( 'elements/widget/controls/common/default', this.resetCommonControls.bind( this ) );
		elementor.hooks.addFilter( 'elements/widget/controls/common-optimized/default', this.resetCommonControls.bind( this ) );
		elementor.hooks.addFilter( 'templates/source/is-remote', this.setSourceAsRemote.bind( this ) );
		elementor.hooks.addFilter( 'elements/base/behaviors', this.filterBehviors.bind( this ), 1000 );
		elementor.hooks.addFilter(
			'component/modal/close',
			this.preventClosingModal.bind( this ),
			1000,
		);

		const types = [
			'core/modal/close/ehp-footer',
			'core/modal/close/ehp-header',
		];

		types.forEach( ( type ) => {
			window.addEventListener( type, this.redirectToHelloPlus.bind( this ) );
		} );

		window.templatesModule = this;
	}

	isElementorDomain() {
		return ehpTemplatePartsEditorSettings?.isElementorDomain;
	}

	preventClosingModal( close, component ) {
		if (
			'library' === component.getNamespace() &&
			'library/templates/ehp-elements' === component?.defaultRoute
		) {
			return () => {};
		}
		return close;
	}

	filterBehviors( behaviors ) {
		if ( this.isElementorDomain() ) {
			return behaviors;
		}

		if ( this.isEhpDocument() ) {
			const { contextMenu: { groups } } = behaviors;
			behaviors.contextMenu.groups = groups
				.map( this.filterOutUnsupportedActions() )
				.filter( ( group ) => group.actions.length );
		}

		return behaviors;
	}

	setSourceAsRemote( isRemote, activeSource ) {
		if ( 'remote-ehp' === activeSource ) {
			return true;
		}

		return isRemote;
	}

	redirectToHelloPlus() {
		if ( this.isElementorDomain() ) {
			return;
		}

		$e.internal( 'document/save/set-is-modified', { status: false } );
		window.location.href = elementor.config.close_modal_redirect_hello_plus + elementor.config.document.type;
	}

	async openSiteIdentity() {
		await $e.run( 'panel/global/open' );
		$e.route( 'panel/global/settings-site-identity' );
	}

	resetCommonControls( commonControls, widgetType ) {
		if ( [ 'ehp-footer', 'ehp-header', 'ehp-flex-footer' ].includes( widgetType ) ) {
			return null;
		}

		return commonControls;
	}

	filterOutUnsupportedActions() {
		return ( group ) => {
			const enabledCommands = elementor.helpers.hasPro()
				? [ 'edit', 'delete', 'resetStyle' ]
				: [ 'edit', 'delete', 'resetStyle', 'save' ];
			const { name, actions } = group;

			return {
				name,
				actions: actions.filter( ( action ) => enabledCommands.includes( action.name ) ),
			};
		};
	}

	isEhpDocument() {
		return [ 'ehp-footer', 'ehp-header' ].includes( elementor.config.document.type );
	}
}
