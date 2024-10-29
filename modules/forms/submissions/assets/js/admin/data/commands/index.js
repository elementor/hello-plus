export { Restore } from './restore';
export { Export } from './export';
export { Referer } from './referer';

export class Index extends $e.modules.CommandData {
	static getEndpointFormat() {
		return 'form-submissions/{id}';
	}
}
