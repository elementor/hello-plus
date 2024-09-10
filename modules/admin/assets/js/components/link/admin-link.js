import { Link } from '@elementor/ui';

export const AdminLink = ( { href, children } ) => {
	return (
		<Link underline="hover" variant="body1" href={ href } >{ children }</Link>
	);
};
