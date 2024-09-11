import { Link } from '@elementor/ui/Link';

export const AdminLink = ( { href, children } ) => {
	return (
		<Link underline="hover" variant="body1" href={ href } >{ children }</Link>
	);
};
