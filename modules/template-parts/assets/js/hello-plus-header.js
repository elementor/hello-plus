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
                buttonToggle: '.ehp-header__button-toggle',
				menuHasChildren: '.menu-item-has-children',
				itemSubMenu: '.menu-item-has-children a',
				navigation: '.ehp-header__navigation',
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
            buttonToggle: document.querySelector( this.settings.selectors.buttonToggle ),
			menuHasChildren: document.querySelectorAll( this.settings.selectors.menuHasChildren ),
			itemSubMenu: document.querySelectorAll( this.settings.selectors.itemSubMenu ),
			navigation: document.querySelector( this.settings.selectors.navigation ),
        };
    }

    bindEvents() {
		if ( this.elements.buttonToggle ) {
			this.elements.buttonToggle.addEventListener( 'click', () => this.toggleNavigation() );
		}

		if ( this.elements.itemSubMenu.length > 0 ) {
			this.elements.itemSubMenu.forEach( ( item ) => {
				item.addEventListener( 'click', ( event ) => this.toggleSubMenu( event ) );
			} );
		}

		window.addEventListener( 'resize', () => this.onResize() );

		this.onInit();
    }

	onInit() {
		this.handleAriaAttributesMenu();
		this.handleAriaAttributesDropdown();
	}

	onResize() {
		this.handleAriaAttributesMenu();
	}

	handleAriaAttributesDropdown() {
		this.elements.menuHasChildren.forEach( ( item ) => {
			item.querySelector( 'a' ).setAttribute( 'aria-expanded', 'false' );
			item.querySelector( '.sub-menu' ).setAttribute( 'aria-hidden', 'true' );
		} );
	}

	handleAriaAttributesMenu() {
		if ( this.isResponsiveBreakpoint() ) {
			this.elements.buttonToggle.setAttribute( 'aria-expanded', 'false' );
			this.elements.navigation.setAttribute( 'aria-hidden', 'true' );
		}
	}

	toggleSubMenu( event ) {
		event.preventDefault();
		const subMenu = event.target.nextElementSibling;
		const itemTarget = event.target;
		const ariaHidden = subMenu.getAttribute( 'aria-hidden' );

		if ( 'true' === ariaHidden ) {
			itemTarget.setAttribute( 'aria-expanded', 'true' );
			subMenu.setAttribute( 'aria-hidden', 'false' );
		} else {
			itemTarget.setAttribute( 'aria-expanded', 'false' );
			subMenu.setAttribute( 'aria-hidden', 'true' );
		}
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
			this.elements.buttonToggle.setAttribute( 'aria-expanded', 'true' );
		} else {
			this.elements.navigation.setAttribute( 'aria-hidden', 'true' );
			this.elements.buttonToggle.setAttribute( 'aria-expanded', 'false' );
		}
    }
}

document.addEventListener( 'DOMContentLoaded', () => {
    new elementorHelloPlusHeaderHandler();
} );
