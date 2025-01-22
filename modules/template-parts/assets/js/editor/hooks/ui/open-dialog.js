export class OpenDialog extends $e.modules.hookUI.After {
    getCommand() {
        console.log( 'OpenDialog', document.querySelector( '#elementor-panel-category-layout' ) );
        return 'panel/elements/categories';
    }

    getId() {
        return 'ehp-select-on-open-dialog';
    }

    getConditions() {
        return true;
    }

    apply() {
        console.log( document.querySelector( '#elementor-panel-category-layout' ), 'OpenDialog' );
    }
}

export default OpenDialog;
