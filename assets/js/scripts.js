jQuery( document ).ready( function ( $ ) {

	/**
	 * Проверка на блокировку.
	 *
	 * @param $node
	 * @return {bool} True if the DOM Element is UI Blocked, false if not.
	 */
	const is_blocked = function ( $node ) {
		return $node.is( '.processing' ) || $node.parents( '.processing' ).length;
	};

	/**
	 * Блокировка по переданному узлу.
	 *
	 * @param $node
	 */
	const block = function ( $node ) {
		if ( ! is_blocked( $node ) ) {
			$node.addClass( 'processing' ).block( {
				message:    null,
				overlayCSS: {
					background: '#fff',
					opacity:    0.6
				}
			} );
		}
	};

	/**
	 * Снятие блокировки.
	 *
	 * @param $node
	 */
	const unblock = function ( $node ) {
		$node.removeClass( 'processing' ).unblock();
	};

	const AWOF = {
		form:   '.awof-form',
		button: '.awof-button',
		field:  '.awof-phone-input',

		init: function () {


			$( document.body )
				.on( 'submit', AWOF.form, function ( e ) {
					e.preventDefault();

					const $this  = $( e.target )
					const $phone = $this.find( AWOF.field ).val()

					console.log( AWOF.validation($phone) );
					console.log( $phone );

					block( $( e.target ) )
					console.log( e );
					console.log( e.target );
					unblock( $( e.target ) )
				} )
		},

		validation : function ( phone ) {

			if ( phone ) {
				let re = /[^ \(\)\-\+\d]/;
				return ! phone.match( re );
			}

			return false;
		}
	}

	AWOF.init()


} )