import AppBar from '@elementor/ui/AppBar';
import Toolbar from '@elementor/ui/Toolbar';
import { __ } from '@wordpress/i18n';
import HomeIcon from '@elementor/icons/HomeIcon';
import HelpIcon from '@elementor/icons/HelpIcon';
import { AdminTopBarLinks } from './admin-top-bar-links';
import { AdminTopBarLink } from './admin-top-bar-link';

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

export const AdminTopBar = () => {
	return (
		<AppBar position="static" elevation={ 6 }>
			<Toolbar sx={ { alignItems: 'center', backgroundColor: 'background.default', justifyContent: 'space-between' } } padding={ 2 }>
				<AdminTopBarLink linkData={ home } />
				<AdminTopBarLinks linksData={ adminTopBarLinks } />
			</Toolbar>
		</AppBar>
	);
};
