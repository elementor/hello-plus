import Stack from '@elementor/ui/Stack';
import { LinkWithIconAndTitle } from '../link/link-with-icon-and-title';
import Typography from '@elementor/ui/Typography';

export const ColumnLinkGroup = ( { links, title = '' } ) => {
	return (
		<Stack direction="column" gap={ 3 }>
			{ title && ( <Typography variant="h6">{ title }</Typography> ) }
			{ links.map( ( link ) => <LinkWithIconAndTitle key={ link.title } { ...link } /> ) }
		</Stack>
	);
};
