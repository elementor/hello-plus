class elementorHelloPlusHeaderHandler {
    constructor() {
        this.initSettings();
        this.initElements();
        this.bindEvents();
    }

    initSettings() {
        this.settings = {
            selectors: {
                main: '.ehp-header',
                navigationToggle: '.ehp-header__button-toggle',
				dropdownToggle: '.ehp-header__dropdown-toggle',
				navigation: '.ehp-header__navigation',
				dropdown: '.ehp-header__dropdown',
            },
			constants: {
				mobilePortrait: 767,
				tabletPortrait: 1024,
				mobile: 'mobile',
				tablet: 'tablet',
				desktop: 'desktop',
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
			this.onInit();
		}
    }

	onInit() {
		this.handleAriaAttributesMenu();
		this.handleAriaAttributesDropdown();
	}

	onResize() {
		this.handleAriaAttributesMenu();
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
		const subMenu = event.target.nextElementSibling;
		const itemTarget = event.target;
		const ariaHidden = subMenu.getAttribute( 'aria-hidden' );

		if ( 'true' === ariaHidden ) {
			this.openSubMenu( itemTarget, subMenu );
		} else {
			this.closeSubMenu( itemTarget, subMenu );
		}
	}

	openSubMenu( itemTarget, subMenu ) {
		itemTarget.setAttribute( 'aria-expanded', 'true' );
		subMenu.setAttribute( 'aria-hidden', 'false' );
	}

	closeSubMenu( itemTarget, subMenu ) {
		itemTarget.setAttribute( 'aria-expanded', 'false' );
		subMenu.setAttribute( 'aria-hidden', 'true' );
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

document.addEventListener( 'DOMContentLoaded', () => {
    new elementorHelloPlusHeaderHandler();
} );
