import { basePropTypes } from './index';

export default function Upload( props ) {
	const value = props.value.split( ' , ' );
	const isAttached = 'attached' === value[ 0 ];

	if ( isAttached ) {
		const message = value.length > 1
			? __( "Attachments to email won't be saved.", 'elementor-pro' )
			: __( "Attachment to email won't be saved.", 'elementor-pro' );

		return <span>{ message }</span>;
	}

	return value.map( ( path ) => (
		<div key={ path }>
			<a href={ path } target="_blank" rel="noreferrer">
				{ path }
			</a>
		</div>
	) );
}

Upload.propTypes = { ...basePropTypes };
