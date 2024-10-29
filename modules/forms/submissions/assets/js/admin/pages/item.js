/* eslint-disable jsx-a11y/anchor-is-valid */
import WpTable from '../components/wp-table';
import { useNavigate } from '@reach/router';
import { useNoticesContext } from '../context/notices-context';
import PostBox from '../components/post-box';
import FormActionsLog from '../components/form-actions-log';
import noticeMessages from '../notice-messages';
import useDataAction, { STATUS_LOADING } from '../hooks/use-data-action';
import { formatToLocalDateTime } from '../utils/date';
import SubmissionValue from '../components/submission-value';
import { useSettingsContext } from '../context/settings-context';

const { useEffect, useState, useMemo } = React;

export default function Item( props ) {
	const [ { data }, setFetchResult ] = useState( { data: {}, meta: {} } ),
		[ error, setError ] = useState( null ),
		[ isEditMode, setIsEditMode ] = useState( false ),
		[ formData, setFormData ] = useState( {} ),
		navigate = useNavigate(),
		{ notifySuccess, notifyError } = useNoticesContext(),
		{ isTrashEnabled } = useSettingsContext();

	const fields = useMemo(
		() => data.form?.fields.reduce( ( current, field ) => ( {
			...current,
			[ field.id ]: field,
		} ), {} ),
		[ data.form?.fields ],
	);

	// Fetch a single submission on first render
	useEffect( () => {
		$e.data.get( 'form-submissions/index', { id: props.id }, { refresh: true } )
			.then( ( result ) => {
				setFetchResult( result.data );

				return result.data;
			} )
			.then( ( resultData ) => {
				if ( resultData.data.is_read ) {
					return;
				}

				// Mark the submission as read if is not read already
				$e.data.update( 'form-submissions/index', { is_read: true }, { id: props.id } );
			} )
			.catch( ( e ) => setError( e ) );
	}, [] );

	// Move submission to trash
	const [ deleteItem ] = useDataAction( ( [ query = {} ], { abortController } ) => {
		const messages = noticeMessages[ query.force ? 'deleted' : 'trashed' ];

		return $e.data.delete( 'form-submissions/index', { id: props.id, ...query }, { signal: abortController.signal } )
			.then( () => notifySuccess( messages.success( 1 ) ) )
			.then( () => navigate( '/' ) )
			.catch( () => notifyError( messages.error() ) );
	}, [ props.id ] );

	// Update field values.
	const [ update, { status: updateStatus } ] = useDataAction( ( [ values ] ) => {
		return $e.data.update( 'form-submissions/index', { values }, { id: props.id } )
			.then( ( result ) => setFetchResult( result.data ) )
			.then( () => {
				setIsEditMode( false );
				setFormData( {} );
			} )
			.then( () => notifySuccess( noticeMessages.updated.success( 1 ) ) )
			.catch( () => notifyError( noticeMessages.updated.error() ) );
	}, [ props.id ] );

	if ( ! Object.keys( data ).length ) {
		if ( error ) {
			return <p> { error.message || __( 'Not Found', 'elementor-pro' ) } </p>;
		}

		return __( 'Loading...', 'elementor-pro' );
	}

	return (
		<div id="poststuff">
			<form id="post-body" className="metabox-holder columns-2" onSubmit={ ( e ) => {
				e.preventDefault();

				if ( ! isEditMode || updateStatus === STATUS_LOADING ) {
					return;
				}

				update( formData );
			} }>
				<div id="post-body-content">
					<div className="e-form-submissions-main">
						<PostBox
							header={
								<div className="e-form-submissions-main__header">
									<h2>{ __( 'Submission', 'elementor-pro' ) } #{ data.id }</h2>
									<button
										onClick={ ( e ) => {
											e.preventDefault();

											if ( updateStatus === STATUS_LOADING ) {
												return;
											}

											setIsEditMode( ( prev ) => ! prev );
										} }
										className="button button-secondary"
										type="button"
										disabled={ updateStatus === STATUS_LOADING }
									>
										{
											isEditMode
												? __( 'Cancel', 'elementor-pro' )
												: __( 'Edit', 'elementor-pro' )
										}
									</button>
								</div>
							}
						>
							<WpTable className="e-form-submissions-item-table">
								<WpTable.Body>
									{ data.values?.length > 0
										? data.values.map( ( value ) => (
											<WpTable.Row key={ value.id }>
												<WpTable.Cell>
													{ fields?.[ value.key ]?.label || value.key }
												</WpTable.Cell>
												<WpTable.Cell>
													<SubmissionValue
														value={ value.value }
														field={ fields?.[ value.key ] || { id: value.key, type: 'text' } }
														isEditMode={ isEditMode }
														onChange={ ( id, fieldValue ) => setFormData( ( prev ) => ( { ...prev, [ id ]: fieldValue } ) ) }
													/>
												</WpTable.Cell>
											</WpTable.Row>
										) )
										: (
											<WpTable.Row>
												<WpTable.Cell colSpan="2">
													{ __( 'No data', 'elementor-pro' ) }
												</WpTable.Cell>
											</WpTable.Row>
										)
									}
								</WpTable.Body>
							</WpTable>
						</PostBox>
					</div>
					{ data.form_actions_log && <FormActionsLog actions={ data.form_actions_log } /> }
				</div>
				<div className="postbox-container" id="postbox-container-1">
					<PostBox
						header={ <h2>{ __( 'Additional Info', 'elementor-pro' ) } </h2> }
					>
						<div className="submitbox">
							<div id="misc-publishing-actions">
								{ data.post &&
								<div className="misc-pub-section">
									{ __( 'Form:', 'elementor-pro' ) } <a href={ data.post.edit_url } target="_blank" rel="noreferrer">{ data.form.name } (#{ data.form.element_id })</a>
								</div>
								}
								<div className="misc-pub-section">
									{ __( 'Page:', 'elementor-pro' ) } <a href={ data.referer } target="_blank" rel="noreferrer">
										{
											data.referer_title ||
											<i className="eicon-editor-external-link e-form-submissions-referer-icon" />
										}
									</a>
								</div>
								<div className="misc-pub-section">
									{ __( 'Create Date:', 'elementor-pro' ) } <strong>{ formatToLocalDateTime( data.created_at ) }</strong>
								</div>
								<div className="misc-pub-section">
									{ __( 'Update Date:', 'elementor-pro' ) } <strong>{ formatToLocalDateTime( data.updated_at ) }</strong>
								</div>
								{ data.user_name && (
									<div className="misc-pub-section">
										{ __( 'User Name:', 'elementor-pro' ) } <strong>{ data.user_name }</strong>
									</div>
								) }
								{ data.user_ip && (
									<div className="misc-pub-section">
										{ __( 'User IP:', 'elementor-pro' ) } <strong>{ data.user_ip }</strong>
									</div>
								) }
								{ data.user_agent && (
									<div className="misc-pub-section">
										{ __( 'User Agent:', 'elementor-pro' ) } <strong>{ data.user_agent }</strong>
									</div>
								) }
							</div>
							<div id="major-publishing-actions">
								<div id="delete-action">
									<a
										className="submitdelete deletion"
										href="#"
										onClick={ ( e ) => {
											e.preventDefault();

											deleteItem( { force: isTrashEnabled ? 0 : 1 } );
										} }
									>
										{
											isTrashEnabled
												? __( 'Move to Trash', 'elementor-pro' )
												: __( 'Delete Permanently', 'elementor-pro' )
										}
									</a>
								</div>
								<div id="publishing-action">
									<button
										type="submit"
										name="save"
										id="publish"
										className="button button-primary button-large"
										disabled={ ! isEditMode || updateStatus === STATUS_LOADING }
									>
										{ __( 'Update', 'elementor-pro' ) }
									</button>
								</div>
								<div className="clear" />
							</div>
						</div>
					</PostBox>
				</div>
			</form>
			<br className="clear" />
		</div>
	);
}

Item.propTypes = {
	id: PropTypes.string,
};
