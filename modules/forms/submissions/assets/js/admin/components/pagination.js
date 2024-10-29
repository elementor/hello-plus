const { useCallback, useMemo } = React;

/**
 * The characters » ‹, ›, » are flipped automatically in RTL.
 *
 * @see https://developer.mozilla.org/en-US/docs/Mozilla/Localization/Web_Localizability/Creating_localizable_web_applications#D'ont_use_text_as_decoration
 *
 * @param {any} props
 * @return {JSX.Element|string} Element
 * @class
 */
export default function Pagination( props ) {
	const canNavigateBack = useMemo( () => 1 !== props.currentPage, [ props.currentPage ] );
	const canNavigateNext = useMemo( () => props.lastPage > props.currentPage, [ props.currentPage, props.lastPage ] );
	const NavigateBackElement = canNavigateBack ? 'a' : 'span';
	const NavigateNextElement = canNavigateNext ? 'a' : 'span';

	const navigate = useCallback( ( e, shouldNavigate, page ) => {
		e.preventDefault();

		if ( ! shouldNavigate ) {
			return;
		}

		props.onChange( page );
	}, [ props.onChange ] );

	if ( ! props.currentPage ) {
		return '';
	}

	return (
		<div className={ `tablenav-pages ${ props.lastPage <= 1 && 'one-page' }` }>
			<span className="displaying-num">{ props.total } { __( 'items', 'elementor-pro' ) }</span>
			{
				1 < props.lastPage && (
					<span className="pagination-links">
						<NavigateBackElement
							className={ `tablenav-pages-navspan button ${ ! canNavigateBack && 'disabled' }` }
							onClick={ ( e ) => navigate( e, canNavigateBack, 1 ) }
						>
							«
						</NavigateBackElement>
								&nbsp;
						<NavigateBackElement
							className={ `tablenav-pages-navspan button ${ ! canNavigateBack && 'disabled' }` }
							onClick={ ( e ) => navigate( e, canNavigateBack, props.currentPage - 1 ) }
						>
							‹
						</NavigateBackElement>
						<span className="paging-input">
							<span className="screen-reader-text">{ __( 'Current Page', 'elementor-pro' ) }</span>
							<span className="paging-input" style={ { margin: '0 6px' } }>
								<span className="tablenav-paging-text">
									{ props.currentPage } { __( 'of', 'elementor-pro' ) }
									<span className="total-pages" style={ { margin: '0' } }> { props.lastPage }</span>
								</span>
							</span>
						</span>
						<NavigateNextElement
							className={ `tablenav-pages-navspan button ${ ! canNavigateNext && 'disabled' }` }
							onClick={ ( e ) => navigate( e, canNavigateNext, props.currentPage + 1 ) }
						>
							›
						</NavigateNextElement>
								&nbsp;
						<NavigateNextElement
							className={ `tablenav-pages-navspan button ${ ! canNavigateNext && 'disabled' }` }
							onClick={ ( e ) => navigate( e, canNavigateNext, props.lastPage ) }
						>
							»
						</NavigateNextElement>
					</span>
				)
			}
		</div>
	);
}

Pagination.propTypes = {
	currentPage: PropTypes.number,
	total: PropTypes.number,
	lastPage: PropTypes.number,
	perPage: PropTypes.number,
	onChange: PropTypes.func.isRequired,
};
