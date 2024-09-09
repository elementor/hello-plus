import { __ } from '@wordpress/i18n';
import { ActionLinks } from '../components/action-links';

const actionLinks = {
		'install-elementor':
		{
			image: helloPlusAdminData.templateDirectoryURI + '/assets/images/elementor.svg',
			alt: __( 'Elementor', 'hello-plus' ),
			title: __( 'Install Elementor', 'hello-plus' ),
			message: __( 'Create cross-site header & footer using Elementor.', 'hello-plus' ),
			button: __( 'Install Elementor', 'hello-plus' ),
		},
	'activate-elementor':
		{
			image: helloPlusAdminData.templateDirectoryURI + '/assets/images/elementor.svg',
			alt: __( 'Elementor', 'hello-plus' ),
			title: __( 'Activate Elementor', 'hello-plus' ),
			message: __( 'Create cross-site header & footer using Elementor.', 'hello-plus' ),
			button: __( 'Activate Elementor', 'hello-plus' ),
		},
	'style-header-footer':
		{
			image: helloPlusAdminData.templateDirectoryURI + '/assets/images/elementor.svg',
			alt: __( 'Elementor', 'hello-plus' ),
			title: __( 'Style cross-site header & footer', 'hello-plus' ),
			message: __( 'Customize your cross-site header & footer from Elementor’s "Site Settings" panel.', 'hello-plus' ),
			button: __( 'Start Designing', 'hello-plus' ),
		},
	'go-pro':
		{
			image: helloPlusAdminData.templateDirectoryURI + '/assets/images/elementor.svg',
			alt: __( 'Elementor Pro', 'hello-plus' ),
			title: __( 'Go Pro', 'hello-plus' ),
			message: __( 'Unleash Elementor Pro to boost your site.', 'hello-plus' ),
			button: __( 'Yes!', 'hello-plus' ),
		},
	'go-ai':
		{
			image: helloPlusAdminData.templateDirectoryURI + '/assets/images/elementor.svg',
			alt: __( 'Elementor AI', 'hello-plus' ),
			title: __( 'AI Me', 'hello-plus' ),
			message: __( 'Stand on the shoulders of giants.', 'hello-plus' ),
			button: __( 'AI!', 'hello-plus' ),
		},
	'go-image-optimizer':
		{
			image: helloPlusAdminData.templateDirectoryURI + '/assets/images/elementor.svg',
			alt: __( 'Elementor Image Optimizer', 'hello-plus' ),
			title: __( 'Image Optimizer', 'hello-plus' ),
			message: __( 'Smaller is better! Minimize your site size with Elementor Image Optimizer', 'hello-plus' ),
			button: __( 'Go Small', 'hello-plus' ),
		},
};

export const ActionLinksPanel = () => {
	if ( ! helloPlusAdminData.links.length ) {
		return <p>no action links</p>;
	}
	return <ActionLinks
		linksData={ actionLinks } />;
};
