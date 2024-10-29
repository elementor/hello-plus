export default class extends elementorModules.Module {
	constructor() {
		super();

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
