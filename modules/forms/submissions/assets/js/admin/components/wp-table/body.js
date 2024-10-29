export default function Body( props ) {
	return <tbody id="the-list">{ props.children }</tbody>;
}

Body.propTypes = {
	children: PropTypes.any,
};
