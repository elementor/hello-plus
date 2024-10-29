import * as dataCommands from './commands/forms-index';

export default class FormsComponent extends $e.modules.ComponentBase {
	static namespace = 'forms';

	getNamespace() {
		return this.constructor.namespace;
	}

	defaultData() {
		return this.importCommands( dataCommands );
	}
}
