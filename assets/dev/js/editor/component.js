import ControlsHook from './hooks/ui/controls-hook';
export default class extends $e.modules.ComponentBase {
	pages = {};

	getNamespace() {
		return 'hello-plus';
	}

	defaultHooks() {
		return this.importHooks( { ControlsHook } );
	}
}
