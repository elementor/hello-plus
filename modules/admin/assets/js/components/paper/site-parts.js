import { BaseAdminPaper } from './base-admin-paper';
import Stack from '@elementor/ui/Stack';
import { ColumnLinkGroup } from '../linkGroup/column-link-group';
import { __ } from '@wordpress/i18n';
import { useAdminContext } from '../../hooks/use-admin-context';

export const SiteParts = () => {
	const { adminSettings: { siteParts: { siteParts = [], sitePages = [], general = [] } = {} } = {} } = useAdminContext();

	return (
		<BaseAdminPaper>
			<Stack direction="row" gap={ 12 }>
				<ColumnLinkGroup
					title={ __( 'Site Parts', 'hello-plus' ) }
					links={ siteParts }
				/>
				<ColumnLinkGroup
					title={ __( 'Site Pages', 'hello-plus' ) }
					links={ sitePages }
				/>
				<ColumnLinkGroup
					title={ __( 'General', 'hello-plus' ) }
					links={ general }
				/>
			</Stack>
		</BaseAdminPaper>
	);
};
