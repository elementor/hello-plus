import TemplatesModule from './editor/module';

window.addEventListener( 'document/global/templates', () => {
    console.log( 'document/global/templates' );
} );

const HelloPlusTemplates = new TemplatesModule();

window.helloPlusTemplates = HelloPlusTemplates;
