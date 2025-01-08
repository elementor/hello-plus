export default class HelloPlusZigzagFe extends elementorModules.Module {
	constructor() {
		super();

		elementorFrontend.elementsHandler.attachHandler( 'zigzag', [
			() => import( /* webpackChunkName: 'js/content' */ './handlers/hello-plus-zigzag' ),
		] );
	}
}

window.addEventListener( 'elementor/frontend/init', () => {
	new HelloPlusZigzagFe();
} );
