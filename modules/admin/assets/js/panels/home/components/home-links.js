import { Box, Link, Stack, Typography } from '@elementor/ui';

export const HomeLinks = ( { linksColumns } ) => {
	return (
		<Stack direction="row">
			{ linksColumns.map( ( column, index ) => {
				const { title, links } = column;
				return (
					<Box flex="0 0 33%" key={ index }>
						<Stack>
							<Box><Typography variant="h6">{ title } </Typography> </Box>
							{ links.map( ( link, i ) => {
							return (
								<Box key={ link.url }>
									<Link href={ link.url }>{ link.title }</Link>
								</Box>
							);
						} ) }
						</Stack>
					</Box>
				);
			} ) }
		</Stack>
	);
};
