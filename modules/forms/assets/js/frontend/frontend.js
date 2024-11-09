export default class Forms extends elementorModules.Module {
	constructor() {
		super();
		console.log( 'frontend' );
		elementorFrontend.elementsHandler.attachHandler( 'form', [
			() => import( /* webpackChunkName: 'form' */ './handlers/form-steps' ),
			() => import( /* webpackChunkName: 'form' */ './handlers/form-sender' ),
			() => import( /* webpackChunkName: 'form' */ './handlers/form-redirect' ),
			() => import( /* webpackChunkName: 'form' */ './handlers/fields/date' ),
			() => import( /* webpackChunkName: 'form' */ './handlers/recaptcha' ),
			() => import( /* webpackChunkName: 'form' */ './handlers/fields/time' ),
		] );

		elementorFrontend.elementsHandler.attachHandler( 'subscribe', [
			() => import( /* webpackChunkName: 'form' */ './handlers/form-steps' ),
			() => import( /* webpackChunkName: 'form' */ './handlers/form-sender' ),
			() => import( /* webpackChunkName: 'form' */ './handlers/form-redirect' ),
		] );
	}
}

elementorCommon.elements.$window.on( 'elementor/frontend/init', () => {
	console.log( 'init' );
	new Forms();
}	);

