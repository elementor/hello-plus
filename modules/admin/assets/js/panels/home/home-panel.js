import { Box, Stack } from '@elementor/ui';
import HomeHero from './components/home-hero';
import { ActionLinksPanel } from '../action-links-panel';
import { HomeLinks } from './components/home-links';
import { __ } from '@wordpress/i18n';

const linksColumns = [
	{
		title: __( 'My Website Parts', 'hello-plus' ),
		links: [
			{
				title: __( 'Edit Header', 'hello-plus' ),
				url: '#',
			},
			{
				title: __( 'Edit Footer', 'hello-plus' ),
				url: '#',
			},
		],
	},
	{
		title: __( 'My Website Pages', 'hello-plus' ),
		links: [
			{
				title: __( 'Home Page', 'hello-plus' ),
				url: '#',
			},
			{
				title: __( 'About', 'hello-plus' ),
				url: '#',
			},
			{
				title: __( 'Services', 'hello-plus' ),
				url: '#',
			},
			{
				title: __( 'Work', 'hello-plus' ),
				url: '#',
			},
			{
				title: __( 'Contact', 'hello-plus' ),
				url: '#',
			},
		],
	},
	{
		title: __( 'Quick Links', 'hello-plus' ),
		links: [
			{
				title: __( 'Site Nme', 'hello-plus' ),
				url: '#',
			},
			{
				title: __( 'Site Logo', 'hello-plus' ),
				url: '#',
			},
			{
				title: __( 'Edit menu', 'hello-plus' ),
				url: '#',
			},
			{
				title: __( 'Website Colors', 'hello-plus' ),
				url: '#',
			},
			{
				title: __( 'Website fonts', 'hello-plus' ),
				url: '#',
			},
		],
	},
];

export const HomePanel = () => {
	return (
		<Stack direction="row">
			<Box flex="0 0 70%">
				<Stack gap={ 1 }>
					<HomeHero />
					<HomeLinks linksColumns={ linksColumns } />
				</Stack>
			</Box>
			<Box flex="0 0 30%">
				<ActionLinksPanel />
			</Box>
		</Stack>

	);
};
