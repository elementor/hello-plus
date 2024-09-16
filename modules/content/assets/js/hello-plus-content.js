window.addEventListener( 'elementor/frontend/init', () => {
	elementorFrontend.hooks.addAction( 'frontend/element_ready/zigzag.default', ( $scope ) => {
		const scope0 = $scope[ 0 ];
		const motionEffectElements = scope0.querySelectorAll( '.motion-effect-fade-in' );
		const observer = new IntersectionObserver( zigzagObserveHandler, { threshold: 0.1 } );
		motionEffectElements.forEach( () => observer.observe( this ) );
	} );
} );

const zigzagObserveHandler = ( entries, observer ) => {
	entries.forEach( ( entry ) => {
		if ( entry.isIntersecting ) {
			const element = entry.target;
			const delay = element.dataset.motionEffectDelay || 0;
			setTimeout( function() {
				element.classList.add( 'elementor-element-visible' );
			}, delay );

			observer.unobserve( entry.target );
		}
	} );
};
