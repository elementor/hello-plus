import { Stack } from '@elementor/ui/Stack';
import HomeHero from './components/home-hero';
import { HomeLinksGrid } from './components/home-links-grid';
import { __ } from '@wordpress/i18n';
import { GridWithActionLinks } from '../../../../layouts/grids/grid-with-action-links';

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

export const DashboardHomePanel = () => {
	return (
		<GridWithActionLinks>
			<Stack gap={ 1 }>
				<HomeHero />
				<HomeLinksGrid linksColumns={ linksColumns } />
			</Stack>
		</GridWithActionLinks>
	);
};
