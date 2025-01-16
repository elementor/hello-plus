export default class HelloPlusHeaderFe extends elementorModules.Module {
	constructor() {
		super();

		elementorFrontend.elementsHandler.attachHandler( 'ehp-header', [
			() => import( /* webpackChunkName: 'js/header' */ './handlers/hello-plus-header' ),
		] );
	}
}

window.addEventListener( 'elementor/frontend/init', () => {
	new HelloPlusHeaderFe();
} );
