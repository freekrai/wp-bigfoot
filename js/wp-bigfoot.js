(function($) {
	$(function() {
		$('form.draftsforfriends-extend').hide();
		$('a.draftsforfriends-extend').show();
		$('a.draftsforfriends-extend-cancel').show();
		$('a.draftsforfriends-extend-cancel').css('display', 'inline');
		$("a.draftsforfriends-link").show();
		$("div.draftsforfriends-textarea").hide();
	});
	window.draftsforfriends = {
		toggle_extend: function(key) {
			$('#draftsforfriends-extend-form-'+key).show();
			$('#draftsforfriends-extend-link-'+key).hide();
			$('#draftsforfriends-extend-form-'+key+' input[name="expires"]').focus();
		},
		cancel_extend: function(key) {
			$('#draftsforfriends-extend-form-'+key).hide();
			$('#draftsforfriends-extend-link-'+key).show();
		},
		copy_text: function(key) {
			$("div.draftsforfriends-textarea").hide();
			$("a.draftsforfriends-link").show();
			var link = '#link-'+key;
			var button = '#draftsforfriends-button-'+key;
			var textarea = '#textarea-'+key;
			var intextarea = '#inside-textarea-'+key;
			var url = $(link).data('clipboard-text');
			$( link ).hide();
			$( textarea ).show();
			$( intextarea ).focus().select();
			$( textarea ).focusout(function(){
				$( textarea ).hide();
				$( link ).show();
			});
		},
		confirm_delete: function(page,key) {
			if( confirm("Are you sure you want to delete this link?") ){
				var url = 'edit.php?page='+page+'&action=delete&key='+key;
				self.location.href = url;
			}
		}
	};
})(jQuery);
