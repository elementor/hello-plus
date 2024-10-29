export class Export extends $e.modules.CommandData {
	static getEndpointFormat() {
		return 'form-submissions/export/{id}';
	}

	onCatchApply() {
		// Do nothing. (override parent behavior)
	}
}
