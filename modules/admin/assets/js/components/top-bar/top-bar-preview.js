import Stack from '@elementor/ui/Stack';
import ChevronRightIcon from '@elementor/icons/ChevronRightIcon';
import Typography from '@elementor/ui/Typography';
import Button from '@elementor/ui/Button';
import { __ } from '@wordpress/i18n';

export const TobBarPreview = ( { onClickBack, onClickLeftButton, onClickRightButton, overview } ) => {
	return <Stack direction="row" sx={
		{
			alignItems: 'center',
			height: 50,
			px: 2,
			backgroundColor: 'background.default',
			justifyContent: 'space-between',
			borderBottom: '1px solid rgba(0, 0, 0, 0.12)',
		}
	}>
		<Stack
			direction="row"
			spacing={ 1 }
			alignItems="center"
			sx={ { borderRight: '1px solid rgba(0, 0, 0, 0.12)', width: 'fit-content', p: 2, cursor: 'pointer' } }
			onClick={ onClickBack }
		>
			<ChevronRightIcon sx={ { transform: 'rotate(180deg)' } } color="action" />
			<Typography variant="subtitle1" color="action" sx={ { color: 'text.secondary' } }>{ __( 'Back to Wizard', 'hello-plus' ) }</Typography>
		</Stack>
		<Stack direction="row" gap={ 1 }>
			<Button variant="outlined" color="secondary" onClick={ onClickLeftButton }>
				{ overview ? __( 'View Demo', 'hello-plus' ) : __( 'Overview', 'hello-plus' ) }
			</Button>
			<Button variant="contained" color="primary" onClick={ onClickRightButton }>
				{ __( 'Apply Kit', 'hello-plus' ) }
			</Button>
		</Stack>
	</Stack>;
};
