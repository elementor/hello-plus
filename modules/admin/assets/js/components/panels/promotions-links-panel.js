import { PromotionLink } from '../link/promotion-link';
import { useDashboardContext } from '../../hooks/use-dashboard-context';

export const PromotionsLinksPanel = () => {
	const { promotionsLinks } = useDashboardContext();
	return (
		<div className="hello_plus__action_links">
			{ promotionsLinks.map( ( link, i ) => {
			return <PromotionLink key={ i } { ...link } />;
		} ) }
		</div>
	);
};
