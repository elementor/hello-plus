import { Page, TestInfo } from '@playwright/test';
import WpAdminPage from '../pages/wp-admin-page';
import ApiRequests from '../assets/api-requests';

/**
 * Creates a test page with a specified widget
 */
export class WidgetSetup {
	private page: Page;
	private wpAdmin: WpAdminPage;

	constructor( page: Page, testInfo: TestInfo, apiRequests: ApiRequests ) {
		this.page = page;
		this.wpAdmin = new WpAdminPage( page, testInfo, apiRequests );
	}

	/**
	 * Create a test page with the specified widget
	 *
	 * @param {string} widgetName - The exact name of the widget as it appears in the Elementor panel
	 * @return {Promise<void>} - The ID of the created page
	 */
	async createTestPageWithWidget( widgetName: string ): Promise<void> {
		// Navigate to dashboard
		await this.wpAdmin.gotoDashboard();

		// Create a new post with API
		await this.wpAdmin.createNewPostWithAPI();

		// Wait for Elementor to load
		await this.page.waitForSelector( '.elementor-panel' );

		// Add the widget
		await this.page.locator( `.elementor-element-wrapper button:has(.title:text("${ widgetName }"))` )
			.click();

		try {
			// Publishing the page
			await this.page.locator( '.MuiButtonGroup-firstButton' ).click();

			// Wait for save to complete
			await this.page.waitForTimeout( 2000 );
		} catch ( error ) {
			throw error;
		}
	}
}

export default WidgetSetup;
