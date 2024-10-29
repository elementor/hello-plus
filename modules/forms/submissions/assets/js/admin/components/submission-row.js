import Link from './link';
import WpTable from './wp-table';
import { formatToLocalDateTime } from '../utils/date';

const { useState } = React;

export default function SubmissionRow( props ) {
	const [ isMobileRowOpen, setIsMobileRowOpen ] = useState( false );

	const mainValue = props.item?.main?.value || __( 'Unknown', 'elementor-pro' );

	return (
		<WpTable.Row
			className={ isMobileRowOpen ? 'is-expanded' : '' }
			style={ { fontWeight: ! props.item.is_read ? 'bold' : 'inherit' } }
		>
			<WpTable.Cell component="th" className="check-column">
				{ props.checkBoxComponent }
			</WpTable.Cell>
			<WpTable.Cell className="has-row-actions column-primary">
				{ 'trash' === props.item.status
					? mainValue
					: <Link to={ `/${ props.item.id }` } aria-label="View">
						{ mainValue }
					</Link>
				}
				{ props.rowActionComponent }
				<button type="button" className="toggle-row" onClick={ () => setIsMobileRowOpen( ( prev ) => ! prev ) }>
					<span className="screen-reader-text">{ __( 'Show more details', 'elementor-pro' ) }</span>
				</button>
			</WpTable.Cell>
			<WpTable.Cell
				className="column-actions"
				colName={ props.tableTitles.actions_log_status.label }
			>
				{ props.item.actions_count > 0 &&
				<i
					className={ `${
						props.item.actions_count === props.item.actions_succeeded_count
							? 'eicon-success eicon-check-circle-o'
							: 'eicon-error eicon-warning'
					}` }
					title={ `${ props.item.actions_succeeded_count }/${ props.item.actions_count } ${ __( 'Succeed', 'elementor-pro' ) }` }
				/>
				}
			</WpTable.Cell>
			<WpTable.Cell
				className="column-form"
				colName={ props.tableTitles.form.label }
			>
				{
					props.item.post &&
					<a href={ props.item.post.edit_url } target="_blank" rel="noreferrer">
						{ props.item.form.name } ({ props.item.form.element_id })
					</a>
				}
			</WpTable.Cell>
			<WpTable.Cell
				className="column-page"
				colName={ props.tableTitles.page.label }
			>
				<a href={ props.item.referer } target="_blank" rel="noreferrer" title={ props.item.referer_title }>
					{
						props.item.referer_title ||
						<i className="eicon-editor-external-link e-form-submissions-referer-icon" />
					}
				</a>
			</WpTable.Cell>
			<WpTable.Cell
				className="column-id"
				colName={ props.tableTitles.id.label }
			>
				{ props.item.id }
			</WpTable.Cell>
			<WpTable.Cell
				className="column-date"
				colName={ props.tableTitles.created_at.label }
			>
				{ formatToLocalDateTime( props.item.created_at ) }
			</WpTable.Cell>
		</WpTable.Row>
	);
}

SubmissionRow.propTypes = {
	// The resource item that the table present.
	item: PropTypes.object.isRequired,
	tableTitles: PropTypes.objectOf(
		PropTypes.shape( {
			label: PropTypes.string,
		} ),
	),
	rowActionComponent: PropTypes.node,
	checkBoxComponent: PropTypes.node,
};
