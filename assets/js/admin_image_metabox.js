jQuery(document).ready(function($) {

	// Uploading files
	var file_frame;
	var button;

	function upload_listing_image() {
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
		  file_frame.open();
		  return;
		}

		// Create the media frame. 
		file_frame = wp.media.frames.file_frame = wp.media({
		  title: button.data( 'uploader_title' ),
		  button: {
		    text: button.data( 'uploader_button_text' ),
		  },
		  multiple: false
		});

		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			var attachment = file_frame.state().get('selection').first().toJSON();
			var myparent = button.parent().parent();
		  myparent.find( '.'+button.attr('class').replace( '_button', '' ) ).val(attachment.id);
		  myparent.find( 'img' ).attr('src',attachment.url);
		  myparent.find( 'img' ).show();
		  myparent.find( 'p a' ).attr( 'class', 'remove_listing_image_button' );
		  myparent.find( '.remove_listing_image_button' ).text( 'Remove image' );
		});

		// Finally, open the modal
		file_frame.open();
	}; // end upload listing image

	jQuery('#sideImageMeta div div p').on( 'click', '.upload_listing_image_button', function( event ) {
		event.preventDefault();
		button = jQuery(this)
		upload_listing_image(); 
	});

	jQuery('#sideImageMeta div div p').on( 'click', '.remove_listing_image_button', function( event ) {
		event.preventDefault();
		var myparent = jQuery(this).parent().parent();
		myparent.find( 'input' ).val( '' );
		myparent.find( 'img' ).attr( 'src', '' );
		myparent.find( 'img' ).hide();
		jQuery( this ).attr( 'class', 'upload_listing_image_button' );
		jQuery( this ).text( 'Set image ' + jQuery('#sideImageMeta div').index(myparent));
	});

});