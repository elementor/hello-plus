export default class HelloPlusHeaderHandler extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
        return {
            selectors: {
                main: '.ehp-header',
                navigationToggle: '.ehp-header__button-toggle',
				dropdownToggle: '.ehp-header__dropdown-toggle',
				navigation: '.ehp-header__navigation',
				dropdown: '.ehp-header__dropdown',
				wpAdminBar: '#wpadminbar',
            },
			constants: {
				mobilePortrait: 767,
				tabletPortrait: 1024,
				mobile: 'mobile',
				tablet: 'tablet',
				desktop: 'desktop',
				dataScrollBehavior: 'data-scroll-behavior',
				dataBehaviorFloat: 'data-behavior-float',
				scrollUp: 'scroll-up',
				always: 'always',
				none: 'none',
				no: 'no',
			},
        };
    }

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );

		return {
			main: this.$element[ 0 ].querySelector( selectors.main ),
			navigationToggle: this.$element[ 0 ].querySelector( selectors.navigationToggle ),
			dropdownToggle: this.$element[ 0 ].querySelectorAll( selectors.dropdownToggle ),
			navigation: this.$element[ 0 ].querySelector( selectors.navigation ),
			dropdown: this.$element[ 0 ].querySelector( selectors.dropdown ),
			wpAdminBar: document.querySelector( selectors.wpAdminBar ),
		};
	}

    bindEvents() {
		if ( this.elements.navigationToggle ) {
			this.elements.navigationToggle.addEventListener( 'click', () => this.toggleNavigation() );
		}

		if ( this.elements.dropdownToggle.length > 0 ) {
			this.elements.dropdownToggle.forEach( ( menuItem ) => {
				menuItem.addEventListener( 'click', ( event ) => this.toggleSubMenu( event ) );
			} );
		}

		if ( this.elements.main ) {
			window.addEventListener( 'resize', () => this.onResize() );
			window.addEventListener( 'scroll', () => this.onScroll() );
		}
    }

	onInit( ...args ) {
		super.onInit( ...args );

		this.initDefaultState();
	}

	initDefaultState() {
		this.lastScrollY = window.scrollY;

		const { none, no, always, scrollUp } = this.getSettings( 'constants' );

		this.handleAriaAttributesMenu();
		this.handleAriaAttributesDropdown();
		this.handleOffsetTop();

		if ( none === this.getDataScrollBehavior() && no === this.getBehaviorFloat() ) {
			this.setupInnerContainer();
		}

		if ( scrollUp === this.getDataScrollBehavior() || always === this.getDataScrollBehavior() ) {
			this.applyBodyPadding();
		}
	}

	getBehaviorFloat() {
		const { dataBehaviorFloat } = this.getSettings( 'constants' );
		return this.elements.main.getAttribute( dataBehaviorFloat );
	}

	getDataScrollBehavior() {
		const { dataScrollBehavior } = this.getSettings( 'constants' );
		return this.elements.main.getAttribute( dataScrollBehavior );
	}

	setupInnerContainer() {
		this.elements.main.closest( '.e-con-inner' )?.classList.add( 'e-con-inner--ehp-header' );
		this.elements.main.closest( '.e-con' )?.classList.add( 'e-con--ehp-header' );
	}

	onResize() {
		this.handleAriaAttributesMenu();
	}

	onScroll() {
		const { scrollUp, always } = this.getSettings( 'constants' );

		if ( scrollUp === this.getDataScrollBehavior() || always === this.getDataScrollBehavior() ) {
			this.handleScrollDown( this.getDataScrollBehavior() );
		}
	}

	handleOffsetTop() {
		const wpAdminBarOffsetHeight = this.elements.wpAdminBar?.offsetHeight || 0;
		this.elements.main.style.setProperty( '--header-wp-admin-bar-height', `${ wpAdminBarOffsetHeight }px` );
	}

	applyBodyPadding() {
		const mainHeight = this.elements.main.offsetHeight;
		document.body.style.paddingTop = `${ mainHeight }px`;
	}

	handleAriaAttributesDropdown() {
		this.elements.dropdownToggle.forEach( ( item ) => {
			item.nextElementSibling.setAttribute( 'aria-hidden', 'true' );
		} );
	}

	handleAriaAttributesMenu() {
		if ( this.isResponsiveBreakpoint() ) {
			this.elements.navigationToggle.setAttribute( 'aria-expanded', 'false' );
			this.elements.navigation.setAttribute( 'aria-hidden', 'true' );
		}
	}

	toggleSubMenu( event ) {
		event.preventDefault();
		const target = event.target;
		const isSvg = target.classList.contains( 'ehp-header__submenu-toggle-icon' );
		const targetItem = isSvg ? target.parentElement : target;
		const subMenu = isSvg ? target.parentElement.nextElementSibling : target.nextElementSibling;
		const ariaHidden = subMenu.getAttribute( 'aria-hidden' );

		if ( 'true' === ariaHidden ) {
			this.closeAllOtherSubMenus( targetItem );
			this.openSubMenu( targetItem, subMenu );
		} else {
			this.closeSubMenu( targetItem, subMenu );
		}
	}

	closeAllOtherSubMenus( currentTargetItem ) {
		Array.from( this.elements.dropdownToggle ).forEach( ( toggle ) => {
			if ( toggle !== currentTargetItem && 'true' === toggle.getAttribute( 'aria-expanded' ) ) {
				const menu = toggle.nextElementSibling;
				this.closeSubMenu( toggle, menu );
			}
		} );
	}

	openSubMenu( targetItem, subMenu ) {
		targetItem.setAttribute( 'aria-expanded', 'true' );
		subMenu.setAttribute( 'aria-hidden', 'false' );
	}

	closeSubMenu( targetItem, subMenu ) {
		targetItem.setAttribute( 'aria-expanded', 'false' );
		subMenu.setAttribute( 'aria-hidden', 'true' );
	}

	handleScrollDown( behaviorOnScroll ) {
		const currentScrollY = window.scrollY;
		const headerHeight = this.elements.main.offsetHeight;
		const wpAdminBarOffsetHeight = this.elements.wpAdminBar?.offsetHeight || 0;
		const headerFloatOffsetProperty = getComputedStyle( this.elements.main ).getPropertyValue( '--header-float-offset' );
		const headerFloatOffset = parseInt( headerFloatOffsetProperty, 10 ) || 0;
		const totalOffset = headerHeight + wpAdminBarOffsetHeight + headerFloatOffset;

		if ( currentScrollY <= 0 ) {
			this.elements.main.classList.remove( 'scroll-down' );
			this.elements.main.style.removeProperty( '--header-scroll-down' );
			return;
		}

		if ( currentScrollY > this.lastScrollY ) {
			this.elements.main.classList.add( 'scroll-down' );

			const { scrollUp } = this.getSettings( 'constants' );
			if ( scrollUp === behaviorOnScroll ) {
				this.elements.main.style.setProperty( '--header-scroll-down', `${ totalOffset }px` );
			}
		} else {
			this.elements.main.classList.remove( 'scroll-down' );
			this.elements.main.style.removeProperty( '--header-scroll-down' );
		}
		this.lastScrollY = currentScrollY;
	}

	isResponsiveBreakpoint() {
		const responsiveBreakpoint = this.elements.main.getAttribute( 'data-responsive-breakpoint' );

		if ( ! responsiveBreakpoint ) {
			return false;
		}

		const { mobilePortrait, tabletPortrait } = this.getSettings( 'constants' );

		const breakpointValue = 'tablet-portrait' === responsiveBreakpoint ? tabletPortrait : mobilePortrait;

		return window.innerWidth <= breakpointValue;
	}

    toggleNavigation() {
		const isNavigationHidden = this.elements.navigation.getAttribute( 'aria-hidden' );

		if ( 'true' === isNavigationHidden ) {
			this.elements.navigation.setAttribute( 'aria-hidden', 'false' );
			this.elements.navigationToggle.setAttribute( 'aria-expanded', 'true' );
		} else {
			this.elements.navigation.setAttribute( 'aria-hidden', 'true' );
			this.elements.navigationToggle.setAttribute( 'aria-expanded', 'false' );
		}
    }
}
