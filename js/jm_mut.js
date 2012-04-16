jQuery(document).ready(function($) {

	document.jm_mut_delete = function( extension ) {
		ok = confirm("Are you SURE you want to disallow uploading files with " +
		             "an extension matched by '" + extension + "'?" );
		if (!ok) return;
		var data = {
			action: 'jm_mut_delete_type',
			extension_to_delete: extension
		};

	        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
//			alert('jm_mut_delete AJAX response: ' + response);
                        location.reload();
		});
	}

	document.jm_mut_add = function( extension, mimetype ) {
//		ok = confirm("Are you SURE you want to allow uploading files with " +
//		             "an extension matched by '" + extension + "' and a " +
//		             "mimetype of '" + mimetype + "'?");
//		if (!ok) return; 
		var data = { 
			action: 'jm_mut_add_type',
			extension_to_add: extension,
			mimetype_to_add: mimetype
		};
		jQuery.post(ajaxurl, data, function(response) { 
//			alert('jm_mut_add AJAX response: ' + response);
                        location.reload();
		}); 
	}
});

