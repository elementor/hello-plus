import { type Page } from '@playwright/test';
import { WindowType } from '../types/types';

/**
 * Form activity tracking interface
 */
export interface FormActivity {
	ajaxRequested: boolean;
	formSubmitDetected: boolean;
}

/**
 * Form submission indicators interface
 */
export interface FormSubmissionIndicators {
	ajaxRequested: boolean;
	formSubmitDetected: boolean;
	formWasSubmitted: boolean;
	formErrorDetected: boolean;
	formAttemptedSubmission: boolean;
	serverErrorVisible: boolean;
}

/**
 * Utility to track form submission through multiple channels
 *
 * @param {Page} page - The Playwright page object
 * @return {Promise<{formActivity: {ajaxRequested: boolean, formSubmitDetected: boolean}, formSubmissionPromise: Promise<boolean>}>} - Tracking objects and promises
 */
export async function setupFormSubmissionTracking( page: Page ): Promise<{
	formActivity: FormActivity;
	formSubmissionPromise: Promise<boolean>;
}> {
	// Create tracking object
	const formActivity: FormActivity = {
		ajaxRequested: false,
		formSubmitDetected: false,
	};

	// Set up network request tracking
	const formSubmissionPromise = page.waitForRequest(
		( request ) =>
			request.url().includes( 'admin-ajax.php' ) &&
			'POST' === request.method() &&
			'elementor_pro_forms_send_form' === request.postDataJSON()?.action,
		{ timeout: 10000 },
	).then( () => {
		formActivity.ajaxRequested = true;
		formActivity.formSubmitDetected = true;
		return true;
	} ).catch( () => false );

	// Set up client-side event listeners
	await page.evaluate( () => {
		( window as WindowType ).formWasSubmitted = false;
		( window as WindowType ).formErrorDetected = false;

		document.addEventListener( 'submit', () => {
			( window as WindowType ).formWasSubmitted = true;
		}, true );

		document.addEventListener( 'error', ( e ) => {
			if ( e.target instanceof HTMLElement && e.target.classList.contains( 'elementor-form' ) ) {
				( window as WindowType ).formErrorDetected = true;
			}
		}, true );
	} );

	return { formActivity, formSubmissionPromise };
}

/**
 * Checks all form submission indicators
 *
 * @param {Page}                                                  page                   - The Playwright page object
 * @param {{ajaxRequested: boolean, formSubmitDetected: boolean}} formActivity           - Form activity tracking object
 * @param {boolean}                                               formSubmissionDetected - Whether form submission was detected via promise
 * @return {Promise<{ajaxRequested: boolean, formSubmitDetected: boolean, formWasSubmitted: boolean, formErrorDetected: boolean, formAttemptedSubmission: boolean, serverErrorVisible: boolean}>} - Combined indicators
 */
export async function checkFormSubmissionIndicators(
	page: Page,
	formActivity: FormActivity,
	formSubmissionDetected: boolean,
): Promise<FormSubmissionIndicators> {
	// Get client-side detection results
	const clientSideData = await page.evaluate( () => {
		return {
			formWasSubmitted: ( window as WindowType ).formWasSubmitted || false,
			formErrorDetected: ( window as WindowType ).formErrorDetected || false,
		};
	} );

	// Check HTML for form submission indicators
	const formHtml = await page.content();
	const formAttemptedSubmission = formHtml.includes( 'elementor-form-waiting' ) ||
		formHtml.includes( 'elementor-message-danger' ) ||
		formHtml.includes( 'server error' );

	const serverErrorVisible = await page.locator( 'text=server error' ).isVisible();

	// Combine all indicators
	return {
		ajaxRequested: formActivity.ajaxRequested,
		formSubmitDetected: formActivity.formSubmitDetected || formSubmissionDetected,
		formWasSubmitted: clientSideData.formWasSubmitted,
		formErrorDetected: clientSideData.formErrorDetected,
		formAttemptedSubmission,
		serverErrorVisible,
	};
}
