export default function RowActions( props ) {
	return (
		<div className="row-actions" style={ { fontWeight: 'normal' } }>
			{
				props.actions.map( ( action, index ) => {
					const isLastAction = index + 1 === props.actions.length;
					const ActionComponent = action.component || 'a';

					return (
						<span key={ action.key } className={ action.className }>
							<ActionComponent
								href="#"
								aria-label={ action.label }
								onClick={ ( e ) => {
									e.preventDefault();

									action.onApply( props.item );
								} }
								{ ...( action.props ? action.props( props.item ) : {} ) }
							>
								{ action.label }
							</ActionComponent>
							{ ! isLastAction && ( <span>&nbsp;|&nbsp;</span> ) }
						</span>
					);
				} )
			}
		</div>
	);
}

RowActions.propTypes = {
	actions: PropTypes.arrayOf(
		PropTypes.shape( {
			key: PropTypes.string.isRequired,
			label: PropTypes.string.isRequired,
			onApply: PropTypes.func,
			className: PropTypes.string,
			props: PropTypes.func,
			component: PropTypes.any,
		} ),
	),
	// The resource item that the table present.
	item: PropTypes.object,
};
