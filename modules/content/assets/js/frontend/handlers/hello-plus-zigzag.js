export default class ZigZagHandler extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				main: '.ehp-zigzag',
				itemWrapper: '.ehp-zigzag__item-wrapper',
			},
			constants: {
				entranceAnimation: 'zigzag_animation',
				hasEntranceAnimation: 'has-entrance-animation',
				none: 'none',
				visible: 'visible',
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );

		return {
			main: this.$element[ 0 ].querySelector( selectors.main ),
			itemWrappers: this.$element[ 0 ].querySelectorAll( selectors.itemWrapper ),
		};
	}

	bindEvents() {
		if ( this.elements.itemWrappers.length > 0 ) {
			this.elements.itemWrappers.forEach( ( itemWrapper ) => {
				itemWrapper.addEventListener( 'animationend', this.removeAnimationClasses.bind( this ) );
			} );
		}
	}

	getResponsiveSetting( controlName ) {
		const currentDevice = elementorFrontend.getCurrentDeviceMode();
		return elementorFrontend.utils.controls.getResponsiveControlValue( this.getElementSettings(), controlName, '', currentDevice );
	}

	initEntranceAnimation() {
		const { entranceAnimation, none } = this.getSettings( 'constants' );
		const entranceAnimationClass = this.getResponsiveSetting( entranceAnimation );

		if ( ! entranceAnimationClass || none === entranceAnimationClass ) {
			return;
		}

		const observerCallback = ( entries ) => {
			const sortedEntries = [ ...entries ].sort( ( a, b ) => {
				const indexA = a.target.dataset.index;
				const indexB = b.target.dataset.index;
				return indexA - indexB;
			} );

			sortedEntries.forEach( ( entry, index ) => {
				if ( entry.isIntersecting ) {
					setTimeout( () => {
						entry.target.classList.add( entranceAnimationClass );
					}, index * 400 );
				}
			} );
		};

		const observerOptions = {
			root: null,
			rootMargin: '0px',
			threshold: 0.5,
		};
		const observer = new IntersectionObserver( observerCallback, observerOptions );

		this.elements.itemWrappers.forEach( ( element ) => observer.observe( element ) );
	}

	removeAnimationClasses( event ) {
		const { entranceAnimation, visible } = this.getSettings( 'constants' );
		const element = event.target;
		const entranceAnimationClass = this.getResponsiveSetting( entranceAnimation );

		element.classList.remove( entranceAnimationClass );
		element.classList.add( visible );
	}

	onInit( ...args ) {
		const { hasEntranceAnimation } = this.getSettings( 'constants' );

		super.onInit( ...args );

		if ( this.elements.main && this.elements.main.classList.contains( hasEntranceAnimation ) ) {
			this.initEntranceAnimation();
		}
	}
}
