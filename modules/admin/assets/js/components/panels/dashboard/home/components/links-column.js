import { Grid, Stack, Typography } from '@elementor/ui';
import { AdminLink } from '../../../../link/admin-link';

export const LinksColumn = ( { linksColumn } ) => {
	const { title, links } = linksColumn;

	return (
		<Grid item xs={ 6 } lg={ 4 } >
			<Stack gap={ 1 }>
				<Typography variant="h6" sx={ { mb: 1 } }>{ title }</Typography>
				{ links.map( ( link, i ) => {
					return <AdminLink href={ link.url } key={ i }>{ link.title }</AdminLink>;
				} ) }
			</Stack>
		</Grid>
	);
};