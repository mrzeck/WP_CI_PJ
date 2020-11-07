/**
 * FACEBOOK CHAT
 */
$(function() {
	
	body = $(document);

	body.on('click','#fbm_header', function () {
		$('#fbm_content').slideToggle();
	})

	body.on('click','#fbm_close', function () {
		$('#fbm_box').slideToggle();
	})

	body.on('click','#fbm_box_show', function () {
		$('#fbm_box').slideToggle();
		return false;
	})
})
