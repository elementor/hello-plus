import { Grid } from '@elementor/ui/Grid';
import { LinksColumn } from './links-column';

export const HomeLinksGrid = ( { linksColumns } ) => {
	return (
		<Grid container spacing={ 1 }>
			{ linksColumns.map( ( column, index ) => <LinksColumn linksColumn={ column } key={ index } /> ) }
		</Grid>
	);
};
