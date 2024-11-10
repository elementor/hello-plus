export default class Forms extends elementorModules.Module {
	constructor() {
		super();
		console.log( 'frontend' );
		elementorFrontend.elementsHandler.attachHandler( 'form-lite', [
			() => import( /* webpackChunkName: 'form-lite' */ './handlers/form-steps' ),
			() => import( /* webpackChunkName: 'form-lite' */ './handlers/form-sender' ),
			() => import( /* webpackChunkName: 'form-lite' */ './handlers/form-redirect' ),
			() => import( /* webpackChunkName: 'form-lite' */ './handlers/fields/date' ),
			() => import( /* webpackChunkName: 'form-lite' */ './handlers/recaptcha' ),
			() => import( /* webpackChunkName: 'form-lite' */ './handlers/fields/time' ),
		] );

		elementorFrontend.elementsHandler.attachHandler( 'subscribe', [
			() => import( /* webpackChunkName: 'form' */ './handlers/form-steps' ),
			() => import( /* webpackChunkName: 'form' */ './handlers/form-sender' ),
			() => import( /* webpackChunkName: 'form' */ './handlers/form-redirect' ),
		] );
	}
}

elementorCommon.elements.$window.on( 'elementor/frontend/init', () => {
	new Forms();
}	);

