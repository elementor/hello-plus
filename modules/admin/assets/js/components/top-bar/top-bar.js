import AppBar from '@elementor/ui/AppBar';
import Toolbar from '@elementor/ui/Toolbar';
import { __ } from '@wordpress/i18n';
import HomeIcon from '@elementor/icons/HomeIcon';
import HelpIcon from '@elementor/icons/HelpIcon';
import { TopBarLinks } from './top-bar-links';
import { TopBarLink } from '../link/top-bar-link';

const home = {
	label: __( 'Hello+', 'hello-plus' ),
	hrefStr: '#',
	children: <HomeIcon />,
	color: 'primary',
	aria: 'menu',
};

const adminTopBarLinks = [
	{
		label: 'Todo1',
		hrefStr: '#',
		children: <HelpIcon />,
		aria: 'todo-1',
	},
	{
		label: 'Todo2',
		hrefStr: '#',
		children: <HelpIcon />,
		aria: 'todo-2',
	},
];

export const TopBar = () => {
	return (
		<AppBar position="static" elevation={ 6 }>
			<Toolbar sx={ { alignItems: 'center', backgroundColor: 'background.default', justifyContent: 'space-between' } } padding={ 2 }>
				<TopBarLink linkData={ home } />
				<TopBarLinks linksData={ adminTopBarLinks } />
			</Toolbar>
		</AppBar>
	);
};
