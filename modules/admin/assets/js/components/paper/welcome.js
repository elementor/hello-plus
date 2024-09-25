import Typography from '@elementor/ui/Typography';
import { __ } from '@wordpress/i18n';
import { useAdminContext } from '../../hooks/use-admin-context';
import Stack from '@elementor/ui/Stack';
import Button from '@elementor/ui/Button';
import { BaseAdminPaper } from './base-admin-paper';

export const Welcome = () => {
	const { adminSettings: { welcome = [] } } = useAdminContext();

	return (
		<BaseAdminPaper>
			<Typography variant="h6">{ __( 'Welcome to Hello Plus', 'hello-plus' ) }</Typography>
			<Typography variant="body2" sx={ { mb: 3 } }>
				{ __( 'Here you’ll find links to some site settings that will help you set up and get running as soon as possible. With Hello+ you’ll find creating your website is a breeze.', 'hello-plus' ) }
			</Typography>
			<Stack gap={ 1 } direction="row">
				{
					welcome.map( ( { title, link, variant } ) => {
						return (
							<Button key={ title } href={ link } variant={ variant } >{ title }</Button>
						);
					} )
				}
			</Stack>
		</BaseAdminPaper>
	);
};
