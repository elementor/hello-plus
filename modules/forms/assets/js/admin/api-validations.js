module.exports = function( key, fieldID ) {
	var self = this;
	self.cacheElements = function() {
		this.cache = {
			$button: jQuery( '#elementor_pro_' + key + '_button' ),
			$apiKeyField: jQuery( '#elementor_pro_' + key ),
			$apiUrlField: jQuery( '#elementor_pro_' + fieldID ),
		};
	};
	self.bindEvents = function() {
		this.cache.$button.on( 'click', function( event ) {
			event.preventDefault();
			self.validateApi();
		} );

		this.cache.$apiKeyField.on( 'change', function() {
			self.setState( 'clear' );
		} );
	};
	self.validateApi = function() {
		this.setState( 'loading' );
		var apiKey = this.cache.$apiKeyField.val();

		if ( '' === apiKey ) {
			this.setState( 'clear' );
			return;
		}

		if ( this.cache.$apiUrlField.length && '' === this.cache.$apiUrlField.val() ) {
			this.setState( 'clear' );
			return;
		}

		jQuery.post( ajaxurl, {
			action: self.cache.$button.data( 'action' ),
			api_key: apiKey,
			api_url: this.cache.$apiUrlField.val(),
			_nonce: self.cache.$button.data( 'nonce' ),
		} ).done( function( data ) {
			if ( data.success ) {
				self.setState( 'success' );
			} else {
				self.setState( 'error' );
			}
		} ).fail( function() {
			self.setState();
		} );
	};
	self.setState = function( type ) {
		var classes = [ 'loading', 'success', 'error' ],
			currentClass, classIndex;

		for ( classIndex in classes ) {
			currentClass = classes[ classIndex ];
			if ( type === currentClass ) {
				this.cache.$button.addClass( currentClass );
			} else {
				this.cache.$button.removeClass( currentClass );
			}
		}
	};
	self.init = function() {
		this.cacheElements();
		this.bindEvents();
	};
	self.init();
};
