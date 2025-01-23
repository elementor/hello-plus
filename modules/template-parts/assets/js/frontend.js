export default class HelloPlusHeaderFe extends elementorModules.Module {
	constructor() {
		super();

		elementorFrontend.elementsHandler.attachHandler( 'ehp-header', [
			() => import( /* webpackChunkName: 'js/ehp-hader-fe' */ './handlers/hello-plus-header-fe' ),
		] );
	}
}

window.addEventListener( 'elementor/frontend/init', () => {
	new HelloPlusHeaderFe();
} );
