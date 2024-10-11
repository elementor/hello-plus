import AppBar from '@elementor/ui/AppBar';
import HelpIcon from '@elementor/icons/HelpIcon';
import { TopBarContent } from './top-bar-content';

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
		<AppBar position="absolute" sx={ { width: 'calc(100% - 160px)', top: 0, right: 0, height: 50, backgroundColor: 'background.default' } }>
			<TopBarContent adminTopBarLinks={ adminTopBarLinks } />
		</AppBar>
	);
};
