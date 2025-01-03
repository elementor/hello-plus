export default class Content extends elementorModules.Module {
	constructor() {
		super();

		elementorFrontend.elementsHandler.attachHandler( 'zigzag', [
			() => import( /* webpackChunkName: 'js/content' */ './handlers/hello-plus-zigzag' ),
		] );
	}
}

document.addEventListener( 'elementor/frontend/init', () => {
	new Content();
} );
