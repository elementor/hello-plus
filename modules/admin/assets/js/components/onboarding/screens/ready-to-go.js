import Stack from '@elementor/ui/Stack';
import { Navigation } from '../navigation';
import Typography from '@elementor/ui/Typography';
import Button from '@elementor/ui/Button';

export const ReadyToGo = ( { modalCloseRedirectUrl, title, description, viewSite, customizeSite } ) => {
	return (
		<Stack direction="column" alignItems="center" justifyContent="center">
			<Stack sx={ { maxWidth: 662 } } alignItems="center" justifyContent="center" gap={ 4 }>
				<Navigation />
				<Stack alignItems="center" justifyContent="center" gap={ 4 }>
					<Typography variant="h4" align="center" px={ 2 } sx={ { color: 'text.primary' } }>
						{ title }
					</Typography>
					<Typography variant="body1" align="center" px={ 2 } color="text.secondary">
						{ description }
					</Typography>
					<Stack direction="row" gap={ 1 } mt={ 5 }>
						<Button
							sx={ { flex: 1, padding: '8px 22px' } }
							variant="outlined"
							color="secondary"
							onClick={ () => {
							window.location.href = '/';
						} }>
							{ viewSite }
						</Button>
						<Button
							sx={ { flex: 1, padding: '8px 22px' } }
							variant="contained"
							color="primary"
							onClick={ () => {
							window.location.href = modalCloseRedirectUrl;
						} }>
							{ customizeSite }
						</Button>
					</Stack>
				</Stack>
			</Stack>
		</Stack>
	);
};
