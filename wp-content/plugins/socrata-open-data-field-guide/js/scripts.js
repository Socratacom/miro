jQuery(function(jQuery) {
	jQuery('.custom_upload_image_button').click(function() {
		formfield = jQuery(this).siblings('.custom_upload_image');
		preview = jQuery(this).siblings('.custom_preview_image');
		tb_show('', 'media-upload.php?type=image&TB_iframe=true');
		window.send_to_editor = function(html) {
			imgurl = jQuery('img',html).attr('src');
			classes = jQuery('img', html).attr('class');
			id = classes.replace(/(.*?)wp-image-/, '');
			formfield.val(id);
			preview.attr('src', imgurl);
			tb_remove();
		}
		return false;
	});
	jQuery('.custom_clear_image_button').click(function() {
		var defaultImage = jQuery(this).parent().siblings('.custom_default_image').text();
		jQuery(this).parent().siblings('.custom_upload_image').val('');
		jQuery(this).parent().siblings('.custom_preview_image').attr('src', defaultImage);
		return false;
	});

	// repeatable fields
	jQuery('.meta_box_repeatable_add').live('click', function() {
		// clone
		var row = jQuery(this).siblings('.meta_box_repeatable').find('li:last');
		var clone = row.clone();
		clone.find('input').val('');
		row.after(clone);
		// increment name and id
		clone.find('input')
			.attr('name', function(index, name) {
				return name.replace(/(\d+)/, function(fullMatch, n) {
					return Number(n) + 1;
				});
			})
			.attr('id', function(index, name) {
				return name.replace(/(\d+)/, function(fullMatch, n) {
					return Number(n) + 1;
				});
			});
		//
		return false;
	});
	
	jQuery('.meta_box_repeatable_remove').live('click', function(){
		jQuery(this).closest('li').remove();
		return false;
	});
		
	jQuery('.meta_box_repeatable').sortable({
		opacity: 0.6,
		revert: true,
		cursor: 'move',
		handle: '.handle'
	});
});
















