import Stack from '@elementor/ui/Stack';
import { AdminTopBarLink } from './admin-top-bar-link';

export const AdminTopBarLinks = ( { linksData } ) => {
	linksData.forEach( ( link ) => link.color = 'secondary' );
	return (
		<Stack spacing={ 2 } direction="row-reverse">
			{
				linksData.map( ( linkData, index ) => <AdminTopBarLink linkData={ linkData } key={ index } /> )
			}
		</Stack>
	);
};
