
import { TopBarLinks } from './top-bar-links';
import Stack from '@elementor/ui/Stack';
import SvgIcon from '@elementor/ui/SvgIcon';
import { ReactComponent as ElementorNoticeIcon } from '../../../images/elementor-notice-icon.svg';

export const TopBarContent = ( { adminTopBarLinks = [] } ) => {
	return (
		<Stack direction="row" sx={ { alignItems: 'center', height: 50, px: 2, backgroundColor: 'background.default', justifyContent: 'space-between' } }>
			<SvgIcon fontSize="medium" color="primary">
				<ElementorNoticeIcon />
			</SvgIcon>
			<TopBarLinks linksData={ adminTopBarLinks } />
		</Stack>
	);
};
