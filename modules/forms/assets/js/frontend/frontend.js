export default class Forms extends elementorModules.Module {
	constructor() {
		super();

		elementorFrontend.elementsHandler.attachHandler( 'ehp-form', [
			() => import( /* webpackChunkName: 'js/form-lite' */ './handlers/form-sender' ),
			() => import( /* webpackChunkName: 'js/form-lite' */ './handlers/form-redirect' ),
		] );

		elementorFrontend.elementsHandler.attachHandler( 'subscribe', [
			() => import( /* webpackChunkName: 'js/form-lite' */ './handlers/form-sender' ),
			() => import( /* webpackChunkName: 'js/form-lite' */ './handlers/form-redirect' ),
		] );
	}
}

elementorCommon.elements.$window.on( 'elementor/frontend/init', () => {
	new Forms();
} );

