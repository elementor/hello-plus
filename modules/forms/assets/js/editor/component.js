import * as hooks from './hooks/';

export default class Component extends $e.modules.ComponentBase {
	getNamespace() {
		return 'forms';
	}

	defaultHooks() {
		return this.importHooks( hooks );
	}
}
