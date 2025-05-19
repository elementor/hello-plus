/* eslint-disable no-console */
import { expect } from '@playwright/test';
import { parallelTest as test } from '../parallelTest';
import WpAdminPage from '../pages/wp-admin-page';
import EditorPage from '../pages/editor-page';

let context;
let page;
let wpAdmin;
let editor;

test.beforeEach( async ( { browser, apiRequests }, testInfo ) => {
	// Arrange.
	context = await browser.newContext();
	page = await context.newPage();
	wpAdmin = new WpAdminPage( page, testInfo, apiRequests );
	editor = new EditorPage( page, testInfo );
	await wpAdmin.openNewPage();
	await editor.closeNavigatorIfOpen();
} );

test.afterEach( async () => {
	await context.close();
} );

test( 'Flex Hero widget can be added to the page', async () => {
	// Act.
	await editor.addWidget( 'flex-hero' );

	// Assert.
	const previewFrame = editor.getPreviewFrame();
	const widget = previewFrame.locator( '.ehp-flex-hero' );
	await expect( widget ).toBeVisible();
} );

test( 'Flex Hero widget should have showcase layout preset class', async () => {
	// Arrange.
	await editor.addWidget( 'flex-hero' );
	await editor.openSection( 'layout_section' );

	// Wait for the layout preset control to be visible and ready
	await page.waitForSelector( '.elementor-control-layout_preset .elementor-choices', { state: 'visible' } );

	// Act.
	await editor.togglePreviewMode();

	// Assert.
	const previewFrame = editor.getPreviewFrame();
	const widget = previewFrame.locator( '.ehp-flex-hero' );
	await expect( widget ).toHaveClass( /has-layout-preset-showcase/ );
} );

test( 'Flex Hero widget should have storytelling layout preset class', async () => {
	// Arrange.
	await editor.addWidget( 'flex-hero' );
	await editor.openSection( 'layout_section' );

	// Wait for the layout preset control to be visible and ready
	await page.waitForSelector( '.elementor-control-layout_preset .elementor-choices', { state: 'visible' } );

	// Act.
	// Click the storytelling option using the label (which contains the image)
	const storytellingLabel = page.locator( '.elementor-control-layout_preset .elementor-choices-label[data-tooltip*="Storytelling"]' );
	// Wait for the label to be visible and clickable
	await storytellingLabel.waitFor( { state: 'visible' } );
	await storytellingLabel.click();
	// Wait for the preview to update
	await page.waitForTimeout( 1000 );
	
	await editor.togglePreviewMode();

	// Assert.
	const previewFrame = editor.getPreviewFrame();
	const widget = previewFrame.locator( '.ehp-flex-hero' );
	await expect( widget ).toHaveClass( /has-layout-preset-storytelling/ );
} );
