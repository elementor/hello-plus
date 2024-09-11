import { Grid } from '@elementor/ui/Grid';
import { PromotionsLinksPanel } from '../../components/panels/promotions-links-panel';

export const GridWithActionLinks = ( { children } ) => {
	return (
		<Grid container spacing={ 1 }>
			<Grid item xs={ 12 } lg={ 9 }>
				{ children }
			</Grid>
			<Grid item xs={ 12 } lg={ 3 }>
				<PromotionsLinksPanel />
			</Grid>
		</Grid>

	);
};
