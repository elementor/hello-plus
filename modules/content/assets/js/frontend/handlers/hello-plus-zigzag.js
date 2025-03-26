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
				hidden: 'hidden',
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
		const { entranceAnimation, entranceAnimationAlternate, none, visible, hidden } = this.getSettings( 'constants' );
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
						entry.target.classList.remove( hidden );
						const entryIndex = parseInt( entry.target.dataset.index, 10 );
						const animation = this.hasAlternateAnimation( entryIndex ) ? alternateAnimationClass : entranceAnimationClass;
						entry.target.classList.add( animation );
					}, index * 200 );
				}
			} );
		};

		const observerOptions = {
			root: null,
			rootMargin: '0px',
			threshold: 0.5,
		};
		const observer = new IntersectionObserver( observerCallback, observerOptions );

		this.elements.itemWrappers.forEach( ( element, index ) => {
			element.dataset.index = index;
			observer.observe( element );
		} );
	}

	removeAnimationClasses( event ) {
		const { entranceAnimation, entranceAnimationAlternate, visible, hidden } = this.getSettings( 'constants' );
		const element = event.target;
		const entranceAnimationClass = this.getResponsiveSetting( entranceAnimation );
		const alternateAnimationClass = this.getResponsiveSetting( entranceAnimationAlternate );
		const entryIndex = parseInt( element.dataset.index, 10 );

		const animation = this.hasAlternateAnimation( entryIndex ) ? alternateAnimationClass : entranceAnimationClass;
		element.classList.remove( animation );

		element.classList.remove( hidden );
		element.classList.add( visible );
	}

	hasAlternateAnimation( index ) {
		const { hasAlternateAnimation } = this.getSettings( 'constants' );
		const isEven = 0 === ( index + 1 ) % 2;
		return this.elements.main.classList.contains( hasAlternateAnimation ) && isEven;
	}

	onInit( ...args ) {
		const { hasEntranceAnimation } = this.getSettings( 'constants' );

		super.onInit( ...args );

		if ( this.elements.main && this.elements.main.classList.contains( hasEntranceAnimation ) ) {
			this.initEntranceAnimation();
		}
	}
}
