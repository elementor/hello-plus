export const ActionLink = ( { image, alt, title, message, button, link } ) => {
	return (
		<div className="hello_plus__action_link">
			<img src={ image } alt={ alt } />
			<p className="hello_plus__action_link__title">{ title }</p>
			<p className="hello_plus__action_link__message">{ message }</p>
			<a className="components-button is-secondary" href={ link } target="_blank" rel="noreferrer">{ button }</a>
		</div>
	);
};
