jQuery( function($)	{ // adding $ ability for jQuery
	$( function()	{

		$( '#insert-google-drive' ).click( function(e)	{

			e.preventDefault();

			$( '#google-drive-popup' ).css({
				display: 'block',
				position: 'absolute',
				top: 0,
				left: 0,
				height: '100%',
				width: '100%',
				backgroundColor: 'rgba( 0, 0, 0, 0.8 )',
				zIndex: 1001

			});

			$( '#google-drive-content' ).css({
				width: '783px',
				marginTop: '1%',
				marginLeft: '-391px',
				left: '50%',
				height: '250px',
				backgroundColor: '#FFF'
			});

			// restoring form defaults
			$( '#drive-url' ).val( '' ).css( 'background-color', '#FFF' );

			$( '#drive-height-value' ).val( $( '#drive-height-range' ).val() + 'px' );

			$( '#drive-link' ).attr( 'checked', false );

		});

		$( '#google-drive-close-btn' ).click( function()	{

			$( '#google-drive-popup' ).hide();
		
		});

		$( '#drive-height-range' ).change( function()	{

			$( '#drive-height-value' ).val( $( this ).val() + 'px' );

		});

		$( '#google-drive-insert-btn' ).click( function() {

			// getting values from the form
			var url = $( '#drive-url' ).val();
			var view = ( $( '#drive-view' ).val() != 'grid' ) ? ' view="' + $( '#drive-view' ).val() + '"' : '';
			var height = ( $( '#drive-height-range' ).val() > 290 )	? ' height="' + $( '#drive-height-range' ).val() + '"' : '';
			var link = ( $( '#drive-link' ).is( ':checked' ) ) ? ' link="true"' : '';

			// checking that the url has been entered
			if( url != '' )	{

				// adding shortcode to the editor
				window.send_to_editor( '[google-drive url="' + url + '"' + view + height + link + ']' );

				// closing the popup
				$( '#google-drive-popup' ).hide();

			} else {

				// highlighting empty url field
				$( '#drive-url' ).css( 'background-color', '#DE4036' );

			}

		});
	
	});
});