import { Fragment } from 'react';
import { ActionLink } from './action-link';

export const ActionLinks = ( linksData ) => {
	return (
		<div className="hello_plus__action_links">
			{ helloPlusAdminData.links.map( ( link ) => {
				linksData.linksData[ link.type ].link = link.url;
				return <ActionLink { ...linksData.linksData[ link.type ] } />;
			} ) }
		</div>
	);
};
