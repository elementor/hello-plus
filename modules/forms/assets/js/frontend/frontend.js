export default class ehpForms extends elementorModules.Module {
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

elementorCommon.elements.$window.on( 'elementor/frontend/init', () => {
	new ehpForms();
} );

