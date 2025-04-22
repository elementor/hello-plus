// form-page.ts
import BasePage from './base-page';

export default class FormPage extends BasePage {
	async fillForm( data: { name?: string, email?: string, message?: string } ) {
		// Use more reliable selectors with multiple options
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

		// If no success message is found, check if there's an error message
		// This helps with debugging
		const errorMessage = this.page.locator( '.elementor-message-danger' );
		if ( await errorMessage.count() > 0 && await errorMessage.isVisible() ) {
			console.log( 'Error message found instead of success:', await errorMessage.textContent() );
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
