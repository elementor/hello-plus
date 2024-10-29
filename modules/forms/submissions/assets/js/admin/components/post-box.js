export default function PostBox( props ) {
	return (
		<div className="postbox">
			<div className="postbox-header">
				{ props.header }
			</div>
			<div className="inner">
				{ props.children }
			</div>
		</div>
	);
}

PostBox.propTypes = {
	header: PropTypes.oneOfType( [
		PropTypes.string,
		PropTypes.node,
		PropTypes.arrayOf( PropTypes.node ),
	] ),
	children: PropTypes.oneOfType( [
		PropTypes.node,
		PropTypes.arrayOf( PropTypes.node ),
	] ),
};
