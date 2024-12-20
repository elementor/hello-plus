export default class Content extends elementorModules.Module {
	constructor() {
		super();

		elementorFrontend.elementsHandler.attachHandler( 'zigzag', [
			() => import( /* webpackChunkName: 'js/content' */ './handlers/hello-plus-zigzag' ),
		] );
	}
}

elementorCommon.elements.$window.on( 'elementor/frontend/init', () => {
	new Content();
} );
