jQuery(document).ready(function(){
	jQuery( "#dialogModal" ).dialog({
		autoOpen: false,
		resizable: false,
		height:200,
		modal: true,
		buttons: {
			"Send to All": function() {
				jQuery('#Form_sendEntireListForm').submit();
				jQuery( this ).dialog( "close" );
				jQuery( "#dialogConfirm" ).dialog( "open" );
			},
			Cancel: function() {
				jQuery( this ).dialog( "close" );
			}
		}
	});
	
	jQuery( "#dialogConfirm" ).dialog({
		autoOpen: false
	});
	
	jQuery( "#dialogTestmailConfirm" ).dialog({
		autoOpen: false
	});
	
	
	jQuery('#Form_sendEntireListForm_action_SendEntireList').click(function () {
		jQuery( "#dialogModal" ).dialog( "open" );
		return false;
	});
});