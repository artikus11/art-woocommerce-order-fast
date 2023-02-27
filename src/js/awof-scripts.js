jQuery( function ( $ ) {

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

	/**
	 * Установка маски.
	 *
	 * @param el
	 */
	const addMask = function ( el ) {
		$( el ).inputmask(
			{
				mask:        awof_scripts.setting.mask,
				placeholder: $( el ).attr( 'placeholder' )
			}
		)
	};

	const AWOF = {
		form:           '.awof-form',
		button:         '.awof-button',
		field:          '.awof-phone-input',
		nonce:          '#awof-cart-nonce',
		message:        '<div class="awof-message"></div>',
		error:          '<span class="awof-error">' + awof_scripts.translate.empty_field + '</span>',
		timeoutError:   awof_scripts.setting.timeout_error,
		timeoutSuccess: awof_scripts.setting.timeout_success,

		init: function () {

			$( document.body )
				.on( 'submit', this.form, this.submit )
				.on( 'wc_fragments_loaded', function ( e ) {
					addMask( AWOF.field )
				} )

		},

		request: function ( $form, formData, phone ) {

			$.ajax( {
				type:     'POST',
				dataType: 'json',
				url:      $form.attr( 'action' ),
				data:     formData,

				beforeSend: function ( xhr ) {
					xhr.setRequestHeader( 'X-WP-Nonce', formData.nonce );
				},

				success: function ( response ) {
					unblock( $form )

					if ( response.status === 200 ) {
						AWOF.messageBox( $form, 'success', response.message );

						$( document.body ).trigger( 'awof_trigger_send_form', response );

						setTimeout( function () {
								window.location.href = response.url;
							},
							AWOF.timeoutSuccess
						)

						return false;

					}
				},

				error: function ( response ) {
					unblock( $form )

					if ( response.status === 412 ) {
						AWOF.messageBox( $form, 'error', response.responseJSON.message );
					}

					if ( response.status === 400 ) {
						console.log( response.responseJSON.data.details.awof_phone.message );
						AWOF.setErrorStyle( phone );
					}
				}
			} );
		},

		submit: function ( e ) {
			e.preventDefault();

			const $this    = $( e.target )
			const phone    = $this.find( AWOF.field )
			const formData = {}

			const FD = new FormData( e.target );

			FD.forEach( function ( value, key ) {
				formData[ key ] = value;
			} );

			if ( ! AWOF.validation( phone.val() ) ) {
				AWOF.setErrorStyle( phone );

				return false
			}

			block( $( e.target ) )

			AWOF.request( $this, formData, phone );
		},

		validation: function ( phone ) {

			if ( phone ) {
				let re = /[^ \(\)\-\+\d]/;
				return ! phone.match( re );
			}

			return false;
		},

		setErrorStyle: function ( phone ) {
			phone
				.addClass( 'awof-phone-error' )
				.before( AWOF.error )
				.focus()

			setTimeout( function () {
				phone
					.removeClass( 'awof-phone-error' )
					.prev()
					.remove()

			}, AWOF.timeoutError );
		},

		messageBox: function ( $form, classMessage, message ) {
			$form
				.after( AWOF.message )
				.next()
				.addClass( classMessage )
				.append( message )

			setTimeout( function () {
				$form
					.next()
					.remove()
			}, AWOF.timeoutError );
		},
	}

	AWOF.init()

} )