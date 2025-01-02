export default class ZigZagHandler extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				main: '.ehp-zigzag',
				itemWrapper: '.ehp-zigzag__item-wrapper',
			},
			constants: {
				entranceAnimation: 'zigzag_animation',
				entranceAnimationAlternate: 'zigzag_animation_alternate',
				hasEntranceAnimation: 'has-entrance-animation',
				hasAlternateAnimation: 'has-alternate-animation',
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
		const { entranceAnimation, entranceAnimationAlternate, none, hasAlternateAnimation, visible } = this.getSettings( 'constants' );
		const entranceAnimationClass = this.getResponsiveSetting( entranceAnimation );

		if ( ! entranceAnimationClass || none === entranceAnimationClass ) {
			return;
		}
		const alternateAnimationClass = this.getResponsiveSetting( entranceAnimationAlternate );

		const observerCallback = ( entries ) => {
			const sortedEntries = [ ...entries ].sort( ( a, b ) => {
				const indexA = a.target.dataset.index;
				const indexB = b.target.dataset.index;
				return indexA - indexB;
			} );

			sortedEntries.forEach( ( entry, index ) => {
				if ( entry.isIntersecting && ! entry.target.classList.contains( visible ) ) {
					setTimeout( () => {
						if ( ! entry.target.classList.contains( hasAlternateAnimation ) ) {
							entry.target.classList.add( entranceAnimationClass );
						} else {
							entry.target.classList.add( alternateAnimationClass );
						}
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
		const { entranceAnimation, entranceAnimationAlternate, visible, hasAlternateAnimation } = this.getSettings( 'constants' );
		const element = event.target;
		const entranceAnimationClass = this.getResponsiveSetting( entranceAnimation );

		if ( element.classList.contains( hasAlternateAnimation ) ) {
			const alternateAnimationClass = this.getResponsiveSetting( entranceAnimationAlternate );
			element.classList.remove( alternateAnimationClass );
		} else {
			element.classList.remove( entranceAnimationClass );
		}

		element.classList.add( visible );
	}

	getAlternateAnimationClass( entranceAnimationClass ) {
		return entranceAnimationClass.replace( /Right|Left/g, ( match ) => ( 'Right' === match ? 'Left' : 'Right' ) );
	}

	onInit( ...args ) {
		const { hasEntranceAnimation } = this.getSettings( 'constants' );
		console.log('on initttt');

		super.onInit( ...args );

		if ( this.elements.main && this.elements.main.classList.contains( hasEntranceAnimation ) ) {
			this.initEntranceAnimation();
		}
	}
}
