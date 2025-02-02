export default class ehpFormsLite extends elementorModules.Module {
	constructor() {
		super();

		elementorFrontend.elementsHandler.attachHandler( 'ehp-form', [
			() => import( /* webpackChunkName: 'js/ehp-form-lite' */ './handlers/form-sender' ),
			() => import( /* webpackChunkName: 'js/ehp-form-lite' */ './handlers/form-redirect' ),
		] );

		elementorFrontend.elementsHandler.attachHandler( 'subscribe', [
			() => import( /* webpackChunkName: 'js/ehp-form-lite' */ './handlers/form-sender' ),
			() => import( /* webpackChunkName: 'js/ehp-form-lite' */ './handlers/form-redirect' ),
		] );
	}
}

window.addEventListener( 'elementor/frontend/init', () => {
	new ehpFormsLite();
} );

