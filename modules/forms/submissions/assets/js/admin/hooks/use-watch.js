const { useEffect, useRef } = React;

export default function useWatch( callback, deps ) {
	const isFirstRender = useRef( true );

	useEffect( () => {
		if ( isFirstRender.current ) {
			isFirstRender.current = false;

			return;
		}

		callback();
	}, deps );
}
