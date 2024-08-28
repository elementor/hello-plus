import { __ } from '@wordpress/i18n';
import { ActionLinks } from '../components/action-links.js';

const actionLinks = {
	'install-elementor':
		{
			image: helloPlusAdminData.templateDirectoryURI + '/assets/images/elementor.svg',
			alt: __( 'Elementor', 'hello-plus' ),
			title: __( 'Install Elementor', 'hello-plus' ),
			message: __( 'Create cross-site header & footer using Elementor.', 'hello-plus' ),
			button: __( 'Install Elementor', 'hello-plus' ),
			link: helloPlusAdminData.actionLinkURL,
		},
	'activate-elementor':
		{
			image: helloPlusAdminData.templateDirectoryURI + '/assets/images/elementor.svg',
			alt: __( 'Elementor', 'hello-plus' ),
			title: __( 'Activate Elementor', 'hello-plus' ),
			message: __( 'Create cross-site header & footer using Elementor.', 'hello-plus' ),
			button: __( 'Activate Elementor', 'hello-plus' ),
			link: helloPlusAdminData.actionLinkURL,
		},
	'style-header-footer':
		{
			image: helloPlusAdminData.templateDirectoryURI + '/assets/images/elementor.svg',
			alt: __( 'Elementor', 'hello-plus' ),
			title: __( 'Style cross-site header & footer', 'hello-plus' ),
			message: __( 'Customize your cross-site header & footer from Elementor’s "Site Settings" panel.', 'hello-plus' ),
			button: __( 'Start Designing', 'hello-plus' ),
			link: helloPlusAdminData.actionLinkURL,
		},
};

export const ActionLinksPanel = () => {
	if ( ! helloPlusAdminData.actionLinkType ) {
		return;
	}

	return <ActionLinks { ...actionLinks[ helloPlusAdminData.actionLinkType ] } />;
};
