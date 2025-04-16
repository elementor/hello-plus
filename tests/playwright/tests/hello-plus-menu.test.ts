import { parallelTest as test } from '../parallelTest';
import { expect } from '@playwright/test';
import WpAdminPage from '../pages/wp-admin-page';

test.describe( 'Admin menu', () => {
	test( 'That the menu is visible and has three entries', async ( { page, apiRequests }, testInfo ) => {
		// Arrange.
		const wpAdmin = new WpAdminPage( page, testInfo, apiRequests );

		await wpAdmin.gotoDashboard();
		const isVisible = await wpAdmin.page.locator( '#toplevel_page_hello-biz' ).isVisible();
		expect( isVisible ).toBeTruthy();

		await wpAdmin.page.goto( '/wp-admin/admin.php?page=hello-biz' );
		expect( await wpAdmin.page.locator( '#toplevel_page_hello-biz ul li:not(.wp-submenu-head)' ).count() ).toEqual( 3 );
	} );
} );
