import { PromotionLink } from '../link/promotion-link';
import { useAdminContext } from '../../hooks/use-admin-context';

export const PromotionsList = () => {
	const { promotionsLinks } = useAdminContext();
	return (
		<div className="hello_plus__action_links">
			{ promotionsLinks.map( ( link, i ) => {
				return <PromotionLink key={ i } { ...link } />;
			} ) }
		</div>
	);
};
