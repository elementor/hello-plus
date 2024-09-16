import { ActionLink } from './action-link';

export const ActionLinks = ( linksData ) => {
	return (
		<div className="hello_plus__action_links">
			{ helloPlusAdminData.links.map( ( link, i ) => {
				linksData.linksData[ link.type ].link = link.url;
				return <ActionLink key={ i } { ...linksData.linksData[ link.type ] } />;
			} ) }
		</div>
	);
};
