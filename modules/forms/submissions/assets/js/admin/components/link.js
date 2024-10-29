import { useEffect, useRef } from 'react';
import { Link as BaseLink } from '@reach/router';

export default function Link( props ) {
	const ref = useRef();

	useEffect( () => {
		if ( ! ref.current ) {
			return;
		}

		ref.current.setAttribute( 'href', `${ location.href.split( '#' )[ 0 ] }#${ props.to }` );
	}, [ props.to, ref.current ] );

	return <BaseLink { ...props } ref={ ref } />;
}

Link.propTypes = {
	...BaseLink.propTypes,
};

