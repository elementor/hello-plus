import AppBar from '@elementor/ui/AppBar';
import HelpIcon from '@elementor/icons/HelpIcon';
import { TopBarLinks } from './top-bar-links';
import Stack from '@elementor/ui/Stack';
import SvgIcon from '@elementor/ui/SvgIcon';
import { ReactComponent as ElementorNoticeIcon } from '../../../images/elementor-notice-icon.svg';

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
			<Stack direction="row" sx={ { alignItems: 'center', height: 50, px: 2, backgroundColor: 'background.default', justifyContent: 'space-between' } }>
				<SvgIcon fontSize="medium" color="primary">
					<ElementorNoticeIcon />
				</SvgIcon>
				<TopBarLinks linksData={ adminTopBarLinks } />
			</Stack>
		</AppBar>
	);
};
