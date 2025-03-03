import { __ } from '@wordpress/i18n';
import Component from './component';
import FieldsMapControl from './fields-map-control';
import FieldsRepeaterControl from './fields-repeater-control';

export default class FormsModule extends elementorModules.editor.utils.Module {
	onElementorInit() {
		const ReplyToField = require( './reply-to-field' );

		this.replyToField = new ReplyToField();

		// Form fields
		const AcceptanceField = require( './fields/acceptance' ),
			TelField = require( './fields/tel' );

		this.Fields = {
			tel: new TelField( 'ehp-form' ),
			acceptance: new AcceptanceField( 'ehp-form' ),
		};

		elementor.addControlView( 'Fields_map', FieldsMapControl );
		elementor.addControlView( 'form-fields-repeater', FieldsRepeaterControl );

		elementorPromotionsData.collect_submit = {
			title: __( 'Collect Submissions', 'hello-plus' ),
			description: [ __( 'Upgrade to Pro "Advanced Solo" to access and manage all your form submissions in one place..', 'hello-plus' ) ],
			upgrade_text: __( 'Upgrade', 'hello-plus' ),
			upgrade_url: 'https://go.elementor.com/go-pro-button-widget-control/',
			image: 'https://assets.elementor.com/free-to-pro-upsell/v1/images/cta.jpg',
			image_alt: 'Upgrade',
		};
	}

	showPromotion( targetElement ) {
		const hasProAndNotConnected = elementor.helpers.hasProAndNotConnected(),
			dialogOptions = {
				title: __( 'Dynamic Content', 'elementor' ),
				content: __(
					'Create more personalized and dynamic sites by populating data from various sources with dozens of dynamic tags to choose from.',
					'elementor',
				),
				targetElement,
				position: {
					blockStart: '-10',
				},
				actionButton: {
					url: hasProAndNotConnected
						? elementorProEditorConfig.urls.connect
						: elementor.config.dynamicPromotionURL.replace( '%s', this.view.model.get( 'name' ) ),
					text: hasProAndNotConnected
						? __( 'Connect & Activate', 'elementor' )
						: __( 'Upgrade', 'elementor' ),
				},
			};

		elementor.promotion.showDialog( dialogOptions );
	}

	onElementorInitComponents() {
		$e.components.register( new Component( { manager: this } ) );
	}
}
