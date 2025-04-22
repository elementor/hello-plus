import { Page, TestInfo } from '@playwright/test';
import WpAdminPage from '../pages/wp-admin-page';
import ApiRequests from '../assets/api-requests';
import { PageData } from '../types/types';

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
	 * @return {Promise<PageData>} - The ID and URL of the created page
	 */
	async createTestPageWithWidget( widgetName: string ): Promise<PageData> {
		// Navigate to dashboard
		await this.wpAdmin.gotoDashboard();

		// Create a new post with API
		await this.wpAdmin.createNewPostWithAPI();

		// Wait for Elementor to load
		await this.page.waitForSelector( '.elementor-panel' );

		// Add the widget
		await this.page.locator( `.elementor-element-wrapper button:has(.title:text("${ widgetName }"))` ).click();

		try {
			// Publishing the page
			await this.page.locator( '.MuiButtonGroup-firstButton' ).click();

			// Wait for save to complete
			await this.page.waitForTimeout( 2000 );

			// Extract page ID from the URL
			const currentUrl = this.page.url();
			const postId = this.extractPostIdFromUrl( currentUrl );

			// Create the page URL for frontend viewing
			const pageUrl = `/?p=${ postId }`;

			return { id: postId, url: pageUrl };
		} catch ( error ) {
			throw error;
		}
	}

	/**
	 * Extract post ID from the editor URL
	 *
	 * @param {string} url - The current URL from which to extract the post ID
	 * @return {string} The extracted post ID
	 * @throws {Error} If post ID cannot be extracted from the URL
	 */
	private extractPostIdFromUrl( url: string ): string {
		const match = url.match( /post=(\d+)/ );
		if ( match && match[ 1 ] ) {
			return match[ 1 ];
		}
		throw new Error( 'Could not extract post ID from URL: ' + url );
	}
}

export default WidgetSetup;
