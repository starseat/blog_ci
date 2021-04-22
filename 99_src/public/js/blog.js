function deleteBlog(event) {
	event.preventDefault();
    event.stopPropagation();

	if(confirm('정말 삭제하시겠습니까?')) {
		$('#deleteBlogForm').submit();
	}
}
