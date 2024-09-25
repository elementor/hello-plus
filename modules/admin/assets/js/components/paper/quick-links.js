import { BaseAdminPaper } from './base-admin-paper';
import Typography from '@elementor/ui/Typography';
import { __ } from '@wordpress/i18n';
import Stack from '@elementor/ui/Stack';
import { ColumnLinkGroup } from '../linkGroup/column-link-group';
import PhotoIcon from '@elementor/icons/PhotoIcon';
import BrushIcon from '@elementor/icons/BrushIcon';
import UnderlineIcon from '@elementor/icons/UnderlineIcon';

export const QuickLinks = () => {
	return (
		<BaseAdminPaper>
			<Typography variant="h6">{ __( 'Quick links', 'hello-plus' ) }</Typography>
			<Typography variant="body2" sx={ { mb: 3 } }>
				{ __( 'These quick actions will get your site airborne in a flash.', 'hello-plus' ) }
			</Typography>
			<Stack direction="row" gap={ 9 }>
				<ColumnLinkGroup links={ [ { title: __( 'Site Name', 'hello-plus' ) } ] } />
				<ColumnLinkGroup links={ [ { title: __( 'Site Logo', 'hello-plus' ), Icon: PhotoIcon } ] } />
				<ColumnLinkGroup links={ [ { title: __( 'Site Colors', 'hello-plus' ), Icon: BrushIcon } ] } />
				<ColumnLinkGroup links={ [ { title: __( 'Site Fonts', 'hello-plus' ), Icon: UnderlineIcon } ] } />
			</Stack>
		</BaseAdminPaper>
	);
};
