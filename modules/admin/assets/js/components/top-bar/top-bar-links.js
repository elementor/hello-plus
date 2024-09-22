import Stack from '@elementor/ui/Stack';
import { TopBarLink } from '../link/top-bar-link';

export const TopBarLinks = ( { linksData } ) => {
	linksData.forEach( ( link ) => link.color = 'secondary' );
	return (
		<Stack spacing={ 2 } direction="row-reverse">
			{
				linksData.map( ( linkData, index ) => <TopBarLink linkData={ linkData } key={ index } /> )
			}
		</Stack>
	);
};
