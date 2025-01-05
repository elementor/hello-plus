import * as hooks from './hooks/';

export default class Component extends $e.modules.ComponentBase {
	getNamespace() {
		return 'ehp-templates';
	}

	defaultHooks() {
		console.log( hooks );
		return this.importHooks( hooks );
	}
}
