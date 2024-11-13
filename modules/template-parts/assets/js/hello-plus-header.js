class elementorHelloPlusHeaderHandler {
    constructor() {
        this.initSettings();
        this.initElements();
        this.bindEvents();
		this.lastScrollY = window.scrollY;
    }

    initSettings() {
        this.settings = {
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

    initElements() {
        this.elements = {
            window,
            main: document.querySelector( this.settings.selectors.main ),
            navigationToggle: document.querySelector( this.settings.selectors.navigationToggle ),
			dropdownToggle: document.querySelectorAll( this.settings.selectors.dropdownToggle ),
			navigation: document.querySelector( this.settings.selectors.navigation ),
			dropdown: document.querySelector( this.settings.selectors.dropdown ),
			wpAdminBar: document.querySelector( this.settings.selectors.wpAdminBar ),
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

			this.onInit();
		}
    }

	onInit() {
		const { none, no } = this.settings.constants;

		this.handleAriaAttributesMenu();
		this.handleAriaAttributesDropdown();
		this.handleOffsetTop();

		if ( none === this.getDataScrollBehavior() && no === this.getBehaviorFloat() ) {
			this.setupInnerContainer();
		}
	}

	getBehaviorFloat() {
		const { dataBehaviorFloat } = this.settings.constants;
		return this.elements.main.getAttribute( dataBehaviorFloat );
	}

	getDataScrollBehavior() {
		const { dataScrollBehavior } = this.settings.constants;
		return this.elements.main.getAttribute( dataScrollBehavior );
	}

	setupInnerContainer() {
		this.elements.main.closest( '.e-con-inner' ).classList.add( 'e-con-inner--ehp-header' );
		this.elements.main.closest( '.e-con' ).classList.add( 'e-con--ehp-header' );
	}

	onResize() {
		this.handleAriaAttributesMenu();
	}

	onScroll() {
		const { scrollUp, always } = this.settings.constants;

		if ( scrollUp === this.getDataScrollBehavior() || always === this.getDataScrollBehavior() ) {
			this.handleScrollDown( this.getDataScrollBehavior() );
		}

		if ( always === this.getDataScrollBehavior() ) {
			this.applyBodyPadding();
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
			this.openSubMenu( targetItem, subMenu );
		} else {
			this.closeSubMenu( targetItem, subMenu );
		}
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
		const { scrollUp } = this.settings.constants;

		const currentScrollY = window.scrollY;
		const headerHeight = this.elements.main.offsetHeight;
		const wpAdminBarOffsetHeight = this.elements.wpAdminBar?.offsetHeight || 0;
		const headerFloatOffsetProperty = getComputedStyle( this.elements.main ).getPropertyValue( '--header-float-offset' );
		const headerFloatOffset = parseInt( headerFloatOffsetProperty, 10 ) || 0;
		const totalOffset = headerHeight + wpAdminBarOffsetHeight + headerFloatOffset;

		if ( currentScrollY > this.lastScrollY ) {
			this.elements.main.classList.add( 'scroll-down' );

			if ( scrollUp === behaviorOnScroll ) {
				this.elements.main.style.setProperty( '--header-scroll-down', `${ totalOffset }px` );
			}
		} else {
			this.elements.main.classList.remove( 'scroll-down' );
		}
		this.lastScrollY = currentScrollY;
	}

	isResponsiveBreakpoint() {
		const device = this.getCurrentDevice();
		return this.elements.main.classList.contains( `has-navigation-breakpoint-${ device }-portrait` );
	}

	getCurrentDevice() {
		const { mobilePortrait, tabletPortrait, mobile, tablet, desktop } = this.settings.constants;

		const isMobile = this.elements.window.innerWidth <= mobilePortrait;
		const isTablet = this.elements.window.innerWidth <= tabletPortrait;

		if ( isMobile ) {
			return mobile;
		} else if ( isTablet ) {
			return tablet;
		}
		return desktop;
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

window.addEventListener( 'elementor/frontend/init', () => {
	elementorFrontend.hooks.addAction( 'frontend/element_ready/ehp-header.default', () => new elementorHelloPlusHeaderHandler() );
} );
