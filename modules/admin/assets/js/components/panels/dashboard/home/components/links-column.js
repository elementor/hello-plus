import { Box, Grid, Link, Stack, Typography } from '@elementor/ui';

export const LinksColumn = ( { linksColumn } ) => {
	const { title, links } = linksColumn;

	return (
		<Grid item xs={ 6 } lg={ 4 } >
			<Stack gap={ 1 }>
				<Typography variant="h6" sx={ { mb: 1 } }>{ title }</Typography>
				{ links.map( ( link, i ) => {
					return <Link underline="hover" variant="body1" href={ link.url } key={ i }>{ link.title }</Link>;
				} ) }
			</Stack>
		</Grid>
	);
};
