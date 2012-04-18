jQuery(document).ready(function($) {

	document.jm_mut_delete = function( extension ) {
		ok = confirm("Are you SURE you want to disallow uploading files with " +
		             "an extension matched by '" + extension + "'?" );
		if (!ok) return;
		var data = {
			action: 'jm_mut_delete_type',
			extension_to_delete: extension
		};

	        // The ajaxurl variable should be defined for us and point to admin-ajax.php
		// This requires WordPress version 2.8 or greater.
		jQuery.post(ajaxurl, data, function(response) {
                        location.reload();
		});
	}

	// Add the onclick handler for the add button. 
	$('#jm_mut_add_button')[0].onclick = function() {
		extension = $('#jm_mut_add_extension').val();
		mimetype  = $('#jm_mut_add_mimetype').val();
		var data = { 
			action: 'jm_mut_add_type',
			extension_to_add: extension,
			mimetype_to_add: mimetype
		};
		jQuery.post(ajaxurl, data, function(response) { 
                        location.reload();
		}); 
	}
});

