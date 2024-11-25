import { SelectTemplatePartsOnOpen } from './select-on-open';
import HelloPlusLogo from './hello-plus-logo';

export default class TemplatePartsModule extends elementorModules.editor.utils.Module {
    onElementorInit() {
        this.selectOnOpen = new SelectTemplatePartsOnOpen();
        this.helloPlusLogo = new HelloPlusLogo();
    }

    handleSelectOnOpen() {
        if ( this.selectOnOpen.getConditions() ) {
            this.selectOnOpen.apply();
        }
    }

    onElementorInitComponents() {
		console.log('ofjsieojgieogjeio')
		elementor.hooks.addFilter( 'editor/documents/attach-preview', () => this.handleSelectOnOpen() );
		window.helloPlusLogo = this.helloPlusLogo;
    }
}
