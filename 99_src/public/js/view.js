$(document).ready(function() {
	tag_init();
});

function tag_init() {
	$('#hashtag-box').hide();d
	const tagData = $('#temp_tags').val();
	if(tagData == "") {
		return;
	}

	const $tagList = $('#hashtag-list');
	const tags = JSON.parse(decodeURIComponent(tagData));
	tags.forEach( tag => {
		$tagList.append('<a href="#0">' + tag + '</a>');
	});
	$('#hashtag-box').show();
}
