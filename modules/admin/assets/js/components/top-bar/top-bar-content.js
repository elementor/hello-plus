import Stack from '@elementor/ui/Stack';
import SvgIcon from '@elementor/ui/SvgIcon';
import { ReactComponent as ElementorNoticeIcon } from '../../../images/elementor-notice-icon.svg';
import Typography from '@elementor/ui/Typography';
import XIcon from '@elementor/icons/XIcon';
import { __ } from '@wordpress/i18n';

export const TopBarContent = ( { sx = {}, iconSize = 'medium', onClose } ) => {
	return (
		<Stack direction="row" sx={ { alignItems: 'center', minHeight: 50, px: 2, backgroundColor: 'background.default', justifyContent: 'space-between', ...sx } }>
			<Stack direction="row" spacing={ 1 } alignItems="center">
				<SvgIcon fontSize={ iconSize } color="secondary">
					<ElementorNoticeIcon />
				</SvgIcon>
				<Typography variant="subtitle1" sx={ { color: 'text.primary' } }>{ __( 'Hello+', 'hello-plus' ) }</Typography>
			</Stack>
			{ onClose && ( <XIcon onClick={ onClose } sx={ { cursor: 'pointer', color: 'text.primary' } } /> ) }
		</Stack>
	);
};
