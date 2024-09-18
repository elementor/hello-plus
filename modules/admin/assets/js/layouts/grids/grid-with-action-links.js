import Grid from '@elementor/ui/Grid';
import { PromotionsList } from '../../components/promotions/list';

export const GridWithActionLinks = ( { children } ) => {
	return (
		<Grid container spacing={ 1 }>
			<Grid item xs={ 12 } lg={ 10 }>
				{ children }
			</Grid>
			<Grid item xs={ 12 } lg={ 2 }>
				<PromotionsList />
			</Grid>
		</Grid>

	);
};
