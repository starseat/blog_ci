$(document).ready(function() {	
	toastui_init();

	form_init();

	$('#addNewCategorySubmit').on('click', submitAddNewCategory);
	$(document).on('click.modal', 'a[rel~="modal:close"]', closeAddCategoryModal); 
});

let __editor = null;

function exitWritePage(event, categoryId) {
	event.preventDefault();
    event.stopPropagation();

	let next = '/';
	if(typeof categoryId != 'undefined' && categoryId != null && categoryId != '') {
		next = '/blog/list/' + categoryId;
	}

	location.href = next;
}


function toastui_init() {
	const Editor = toastui.Editor;

	const colorSyntax = Editor.plugin.colorSyntax;
	const codeSyntaxHighlight = Editor.plugin.codeSyntaxHighlight ;
	const tableMergedCell = Editor.plugin.tableMergedCell;
	const uml = Editor.plugin.uml;
	const chart = Editor.plugin.chart;

	const chartOptions = {
		minWidth: 100,
		maxWidth: 600,
		minHeight: 100,
		maxHeight: 300
    };

	__editor = new Editor({
		el: document.querySelector('#blog_content_view'),
		height: '600px',
		initialEditType: 'markdown',
		// initialEditType: 'wysiwyg',
		previewStyle: 'vertical', 
		//initialValue: '<p>sdfsdfsdf<br>\nsdfsdafsdf<br>\n.</p>\n<h1>123</h1>\n<h2>456</h2>', 
		plugins: [colorSyntax, [codeSyntaxHighlight, { highlighter: Prism } ], tableMergedCell, uml, [chart, chartOptions] ], 
		hooks: {
			addImageBlobHook: (blob, callback) => {
				// console.log('[addImageBlobHook] blob:: ', blob);
				sendImageFile(blob, (resultData) => {
					// console.log('[addImageBlobHook.sendImageFile] callback resultData:: ', resultData);
					if(resultData.result) {
						callback(resultData.data, blob.name);
					}
				});
				return false;
			}
		}, 
		language: 'ko-KR',
	});

	//__editor.setHTML('<p>sdfsdfsdf<br>\nsdfsdafsdf .</p>\n<p><br>\nasss</p>\n<p>asdf</p>\n<p>fbfgbfb<br>\nsdfsda<br>\nfsda<br>\nfsdaf<br>\naa</p>\n<p><br>\n<br>\n<br>\n<br>\n<br></p>\n<h1>123</h1>\n<h2>456</h2>\n<blockquote>\n<p>fghfgh</p>\n</blockquote>');

	console.log('[toastui_init] __editor :: ', __editor);
	// console.log('[toastui_init] __editor contents getHtml :: ', __editor.getHtml());
	// console.log('[toastui_init] __editor contents getMarkdown :: ', __editor.getMarkdown());
}

function form_init() {
	load_data();
}

function load_data() {
	if($('#saved_blog_seq').val() == 0) {
		return;
	}

	$('#blog_category').val($('#saved_blog_category').val());
	$('#blog_title').val($('#saved_blog_title').val());
	$('#blog_viewType').val($('#saved_blog_view_type').val());
	$('#blog_thumbnail_temp').attr('src', $('#saved_blog_thumbnail').val());
	
	$('#blog_content_view').summernote('code', $('#blog_content').val());
}


function loadImage(_this) {
	if (_this.files && _this.files[0]) {
		const fr = new FileReader();
		fr.onload = function(_e) {
			$('#blog_thumbnail_temp').attr('src', _e.target.result);
			//$('#product_temp_image_remove').show();
		}
		fr.readAsDataURL(_this.files[0]);
	}
}


function submitBlog(event) {
	event.preventDefault();
    event.stopPropagation();

	if($('#blog_title').val() == '') {
		alert('제목은 필수로 입력해야 합니다.');
		$('#blog_title').focus();
		return false;
	}

	// const blog_content = $('#blog_content_view').summernote('code');
	// if(blog_content == '') {
	// 	alert('내용이 비어있습니다.');
	// 	return false;
	// }
	
	// $('#blog_content').val(blog_content);
	// $('#writeForm').submit();

	console.log('[submitBlog] contents value :: ', __editor.value());
	console.log('[submitBlog] contents getHTML :: ', __editor.getHTML());
	console.log('[submitBlog] contents getMarkdown:: ', __editor.getMarkdown());	
	
}

function showAddCategoryModal(event) {
	event.preventDefault();
    event.stopPropagation();

	$('.toastui-editor-md-mode .toastui-editor-md-container').css('z-index', 0);
	$('.toastui-editor-ww-mode .toastui-editor-ww-container').css('z-index', 0);

	// $.get('/api/admin/category/parents', function(_resultData) {
	// 	const resultObj = JSON.parse(_resultData);
	// 	console.log('[showAddCategoryModal] resultObj:: ', resultObj);
	// 	$('#addCategoryModal').modal({
	// 		escapeClose: false,
	// 		clickClose: false,
	// 		showClose: true, 
	// 		fadeDuration: 100
	// 	});
	// })

	$('#addCategoryModal').modal({
		escapeClose: false,
		clickClose: false,
		showClose: true, 
		fadeDuration: 100
	});
}

function closeAddCategoryModal(event) {
	event.preventDefault();

	$('.toastui-editor-md-mode .toastui-editor-md-container').css('z-index', 100);
	$('.toastui-editor-ww-mode .toastui-editor-ww-container').css('z-index', 100);	
	
	$.modal.close();
}

function submitAddNewCategory(event) {
	event.preventDefault();
    event.stopPropagation();
	
	if(!checkNewCategory_id()) {
		return false;
	}

	if(!checkNewCategory_name()) {
		return false;
	}

	$('#addNewCategoryForm').submit();
}

function checkNewCategory_id() {
	const newCategoryId = $('#addCategoryModal_newCategoryId').val();

	if (newCategoryId == '') {
        //alert('Category ID is required and cannot be empty.');
		alert('카테고리 ID 는 필수로 입력되어야 합니다.');
		$('#addCategoryModal_newCategoryId').focus();
        return false;
    }

	if (!(newCategoryId.length >= 2 && newCategoryId.length <= 16)) {
        //alert('Category ID must be more than 2 and less than 16 characters long.');
		alert('카테고리 ID 는 2 ~ 16 글자로 입력되어야 합니다.');
		$('#addCategoryModal_newCategoryId').focus();
        return false;
    }

	if (!newCategoryId.match(/^[a-z]+$/)) {
        //alert('Category ID can only consist of lowercase alphabetical.');
		alert('카테고리 ID 는 영문자 소문자만 입력 가능합니다.');
		$('#addCategoryModal_newCategoryId').focus();
        return false;
    }

	return true;
}

function checkNewCategory_name() {
	const newCategoryName = $('#addCategoryModal_newCategoryName').val();

    if (newCategoryName == '') {
        //alert('Category Name is required and cannot be empty.');
		alert('카테고리 명은 필수로 입력되어야 합니다.');
		$('#addCategoryModal_newCategoryName').focus();
        return false;
    }

    if (!(newCategoryName.length >= 2 && newCategoryName.length <= 32)) {
		//alert('Category Name must be more than 2 and less than 32 characters long.');
        alert('카테고리 명은 2 ~ 32 글자로 입력되어야 합니다.');
		$('#addCategoryModal_newCategoryName').focus();
        return false;
    }

    if (!newCategoryName.match(/^[a-zA-Z0-9가-힣\/\-\_\+\@]+$/)) {
		//alert('Category Name can only consist of alphabetical, number, korean and some special charcters(-, _, +, @).');
        alert('카테고리 명은 소문자, 대문자, 한글, 숫자로만 입력되어야 합니다.');
		$('#addCategoryModal_newCategoryName').focus();
        return false;
    }

    return true;
}

function sendImageFile(file, callback) {
	const formData = new FormData();
    formData.append('uploadFile', file);
	formData.append('csrf_token_starseat_blog', $('input[name="csrf_token_starseat_blog"]').val());

	const _url = '/api/admin/upload/image';
	$.ajax({
		data : formData,
		type : 'post',
		url : _url,
		cache : false,
		processData : false,
		contentType : false,
		enctype: 'multipart/form-data',
		// contentType: 'multipart/form-data',
		success : function(resultData) {
			const resultObj = JSON.parse(resultData);
			callback(resultObj);
		}, 
		error: function (jqXHR, textStatus, errorThrown) {
			console.log('[sendImageFile] ajax error :: ', textStatus + ' ' + errorThrown);
		}
	});


}
