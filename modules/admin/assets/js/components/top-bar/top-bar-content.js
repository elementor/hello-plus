import { TopBarLinks } from './top-bar-links';
import Stack from '@elementor/ui/Stack';
import SvgIcon from '@elementor/ui/SvgIcon';
import { ReactComponent as ElementorNoticeIcon } from '../../../images/elementor-notice-icon.svg';
import Typography from '@elementor/ui/Typography';
import { __ } from '@wordpress/i18n';

export const TopBarContent = ( { adminTopBarLinks = [], sx = {}, iconSize = 'medium' } ) => {
	return (
		<Stack direction="row" sx={ { alignItems: 'center', height: 50, px: 2, backgroundColor: 'background.default', justifyContent: 'space-between', ...sx } }>
			<Stack direction="row" spacing={ 1 } alignItems="center">
				<SvgIcon fontSize={ iconSize } color="secondary">
					<ElementorNoticeIcon />
				</SvgIcon>
				<Typography variant="subtitle1">{ __( 'Hello+', 'hello-plus' ) }</Typography>
			</Stack>
			<TopBarLinks linksData={ adminTopBarLinks } />
		</Stack>
	);
};