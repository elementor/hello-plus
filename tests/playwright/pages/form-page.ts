/* eslint-disable no-console */
import BasePage from './base-page';

/**
 * Form Page Object Class
 *
 * This class provides methods for interacting with form elements in the Hello Plus plugin.
 * It handles form operations like:
 * - Filling form fields with flexible selector fallbacks for better test reliability
 * - Submitting forms with multiple potential submit button selectors
 * - Detecting success messages and validation errors
 * - Handling expected errors in test environments (particularly email errors)
 *
 * The class extends BasePage to inherit common page functionality and uses multiple
 * selector strategies to maximize compatibility across different form configurations.
 *
 */
export default class FormPage extends BasePage {
	async fillForm( data: { name?: string, email?: string, message?: string } ) {
		if ( data.name ) {
			await this.fillField( 'input[name="name"], input[name="form_fields[name]"], input[placeholder*="Name"]', data.name );
		}

		if ( data.email ) {
			await this.fillField( 'input[name="email"], input[name="form_fields[email]"], input[type="email"], input[placeholder*="Email"]', data.email );
		}

		if ( data.message ) {
			await this.fillField( 'textarea[name="message"], textarea[name="form_fields[message]"], textarea, textarea[placeholder*="Message"]', data.message );
		}
	}

	// Helper method to fill a field with multiple possible selectors
	private async fillField( selector: string, value: string ) {
		const field = this.page.locator( selector );
		if ( await field.count() > 0 ) {
			await field.fill( value );
		} else {
			console.log( `Field not found: ${ selector }` );
		}
	}

	async submitForm() {
		// Try different submit button selectors
		const submitSelectors = [
			'button[type="submit"]',
			'input[type="submit"]',
			'button.elementor-button',
			'button:has-text("Submit")',
			'button:has-text("Send")',
		];

		for ( const selector of submitSelectors ) {
			const button = this.page.locator( selector );
			if ( await button.count() > 0 ) {
				await button.click();
				return;
			}
		}

		throw new Error( 'Submit button not found' );
	}

	async hasSuccessMessage() {
		// Using the exact success message classes from the code
		const successSelectors = [
			'.elementor-message-success', // This is the most specific and reliable selector
			'.elementor-message.elementor-message-success', // Full class combination
			'div[role="alert"].elementor-message-success',
			// Add fallbacks for backward compatibility or other variations
			'.form-success-message',
			'.success-message',
			'div:has-text("Message sent successfully")',
			'div:has-text("Your submission was successful")',
			'div:has-text("Thank you")',
		];

		for ( const selector of successSelectors ) {
			const message = this.page.locator( selector );
			if ( await message.count() > 0 && await message.isVisible() ) {
				// For debugging, log the success message text
				console.log( 'Success message found:', await message.textContent() );
				return true;
			}
		}

		return false;
	}

	async hasSuccessMessageOrAcceptableError() {
		// First check for success message
		const isSuccess = await this.hasSuccessMessage();
		if ( isSuccess ) {
			return true;
		}

		// If we don't have success, check if we have an email-specific error that we can accept
		const errorMessage = this.page.locator( '.elementor-message-danger' );
		if ( await errorMessage.count() > 0 && await errorMessage.isVisible() ) {
			const errorText = await errorMessage.textContent() || '';

			// Clean and format the error message
			// Clean and format the error message
			const cleanText = ( () => {
				// Make a copy of the original text
				let text = errorText;

				// Define patterns that should be repeatedly removed until stable
				const dangerousPatterns = [
					/&([a-z0-9]+|#[0-9]{1,6}|#x[0-9a-f]{1,6});/ig, // HTML entities
					/<[^>]*>/g, // HTML tags
					/javascript:/gi, // JavaScript protocol
					/data:/gi, // Data protocol
					/on\w+\s*=/gi, // Event handlers (onclick, etc.)
				];

				// First replace <br> and <div> with newlines (for formatting)
				text = text.replace( /<br\s*\/?>/gi, '\n' ).replace( /<div[^>]*>/gi, '\n' );

				// Apply dangerous pattern removal repeatedly until the string stabilizes
				let prevText;
				do {
					prevText = text;

					// Apply each dangerous pattern
					for ( const pattern of dangerousPatterns ) {
						text = text.replace( pattern, ' ' );
					}
				} while ( text !== prevText ); // Continue until no more changes

				// Final formatting
				return text
					.replace( /\s{2,}/g, ' ' )
					.split( '\n' )
					.map( ( line ) => line.trim() )
					.filter( ( line ) => line.length > 0 )
					.join( '\n' );
			} )();

			// Display the formatted error
			console.log( 'Error message found instead of success:' );
			console.log( '\n' +
				'┄'.repeat( 60 ) + '\n' +
				cleanText + '\n' +
				'┄'.repeat( 60 ) );

			// If the error is specifically about email sending, consider this acceptable
			if ( errorText.includes( 'server error' ) && errorText.includes( 'Email' ) ) {
				console.log( 'Email server error detected - considering this an acceptable test outcome because we don\'t actually send an email.' );
				return true;
			}
		}

		return false;
	}

	async hasValidationErrors() {
		// Try different error message selectors
		const errorSelectors = [
			'.form-error-message',
			'.elementor-message-danger',
			'.error-message',
			'.elementor-error',
			'div[role="alert"]:has-text("error")',
			'input:invalid',
			'.elementor-field-invalid',
		];

		for ( const selector of errorSelectors ) {
			const message = this.page.locator( selector );
			if ( await message.count() > 0 && await message.isVisible() ) {
				return true;
			}
		}

		return false;
	}
}
