import { parallelTest as test } from '../parallelTest';
import WidgetSetup from '../utils/widget-setup';

test( 'Create test page with form @setup', async ( { page, apiRequests }, testInfo ) => {
	const widgetSetup = new WidgetSetup( page, testInfo, apiRequests );
	await widgetSetup.createTestPageWithWidget( 'Form Lite' );
} );
