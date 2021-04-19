$(document).ready(function() {	
	summernote_init();

	form_init();

	$('#addNewCategorySubmit').on('click', submitAddNewCategory);
});


function summernote_init() {
    // 그냥 보내면 multer 에러 발생하므로 files 관련 element 제거
    // $('#writeForm').submit(function(){
    //     $('input[name=files]').remove();
    // });
    
    document.emojiSource = '/public/vendor/summernote/emoji/tam-emoji/img';

    $('#content').summernote({
        height: 360, // set editor height
        minHeight: null, // set minimum height of editor
        maxHeight: null, // set maximum height of editor
        focus: true,  // set focus to editable area after initializing summernote
        lang: 'ko-KR', // default: 'en-US', 
        placeholder: 'input your message...',
        codemirror: { theme: 'monokai' },  
        // tabsize: 2,
        
        toolbar: [             
            // ['style', ['emoji', 'style', 'add-text-tags' ]],
			['style', ['emoji', 'style', 'add-text-tags', 'bold', 'italic', 'underline','strikethrough', 'clear']],
            ['font', ['bold', 'underline', 'clear', 'strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['fontname', ['fontname']],
            // ['color', ['color']],
			['color', ['forecolor','color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']], 
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']],
        ],
        popover: {
            air: [
                ['color', ['color']],
                ['font', ['bold', 'underline', 'clear']], 
                ['para', ['ul', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']]
            ], 
            image: [
                ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
                ['float', ['floatLeft', 'floatRight', 'floatNone']],
                ['remove', ['removeMedia']]
            ],
            link: [
                ['link', ['linkDialogShow', 'unlink']]
            ],
            table: [
                ['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
                ['delete', ['deleteRow', 'deleteCol', 'deleteTable']],
            ],
        }, 
		fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New','맑은 고딕','궁서','굴림체','굴림','돋움체','바탕체'],
		fontSizes: ['8','9','10','11','12','14','16','18','20','22','24','28','30','36','50','72'], 
        callbacks: {
            onImageUpload: function(files, editor, welEditable) {
                console.log('[onImageUpload] !!!!!!!!');
                let categoryInfo = '<%= JSON.stringify(categoryInfo) %>';
                categoryInfo = JSON.parse(categoryInfo.replaceAll('&#34;', '"'));
                //boardService.sendImageFile(categoryInfo, files[0], editor, welEditable);
                //$summernote.summernote('insertNode', imgNode);

                // for(let i=0; i<files.length; i++) {
                //     boardService.sendImageFile(categoryInfo, this, files[i], editor, welEditable);
                // }
                boardService.sendImageFile(categoryInfo, this, files[0], editor, welEditable);
            }
        },
    });

	$('.note-editable').css('font-size','18px');
}

function summernote_init_addTextTags() {
    $('.add-text-tags').attr('title', 'Additional text styles');
    
    const addTextTagBtns = $('.note-add-text-tags-btn');
    for(let i=0; i<addTextTagBtns.length; i++) {
        let $tempTag = $(addTextTagBtns[i]);
        let classSplit = (($tempTag).attr('class')).split(' ');
        let curItemName = classSplit[classSplit.length - 1];
        let titleMsg = '';

        switch(curItemName) {
            case 'type-del': { titleMsg = 'Deleted text'; } break;
            case 'type-ins': { titleMsg = 'Inserted text'; } break;
            case 'type-small': { titleMsg = 'Fine print'; } break;
            case 'type-mark': { titleMsg = 'Highlighted text'; } break;
            case 'type-var': { titleMsg = 'Variable'; } break;
            case 'type-kbd': { titleMsg = 'User input'; } break;
            case 'type-code': { titleMsg = 'Inline code'; } break;
            case 'type-samp': { titleMsg = 'Sample output'; } break;
            case 'type-cap': { titleMsg = 'First letter large'; } break;
        }
        $tempTag.attr('title', titleMsg);    
    } // end of for(i in addTextTagBtns)
}

function summernote_init_emoji() {
    $('.emoji-menu-tabs td').css('padding', 0);
    $('.emoji-menu .emoji-items-wrap img').css('margin-top', 0).css('margin-bottom', 0);

    $('.emoji-picker').parent().attr('title', 'emoji');
}

function form_init() {
    // const formType = '<%= writeFormType %>';
    // console.log('[form_init] formType :: ', formType);

    // $('#blog_board_form_type').text(formType);
    // if(formType.toLocaleLowerCase() == 'modify') {
    //     let boardData = '<%= JSON.stringify(boardData) %>';
    //     boardData = boardData.replaceAll('&lt;', '<').replaceAll('&gt;', '>').replaceAll('&#34;', '"'); 
    //     boardData = JSON.parse(boardData);
    //     boardData.content = boardData.content.replaceAll('@-_-@', '"');

    //     $('#blog_board_write_title').val(boardData.title);
    //     $('#blog_board_write_view_type').val(boardData.view_type);
    //     $('#blog_board_write_content').summernote('code', boardData.content);
    //     $('#blog_board_write_seq').val(boardData.seq);
    //     if(boardData.thumbnail != '') {
    //         loadThumbnailImage(boardData.thumbnail);
    //     }
    // }

    // $('#viewType').BVSelect({
    // 	width: '180px', 
    // 	searchbox: false, 
	// });

	// const bv_categoryIdList = new  BVSelect({
	// 	selector: '#category',
	// 	searchbox: true,
    //     offset: true, 
	// 	width: '100%'
	// });

}


function loadImage(_this) {
	if (_this.files && _this.files[0]) {
		const fr = new FileReader();
		fr.onload = function(_e) {
			$('#thumbnail_temp').attr('src', _e.target.result);
			//$('#product_temp_image_remove').show();
		}
		fr.readAsDataURL(_this.files[0]);
	}
}


function submitBlog(event) {
	event.preventDefault();
    event.stopPropagation();

	console.log('category: ', $('#category').val());
	console.log('title: ', $('#title').val());
	console.log('viewType: ', $('#viewType').val());
	console.log('category: ', $('#content').summernote('code'));
	return false;
	
}

function showAddCategoryModal(event) {
	event.preventDefault();
    event.stopPropagation();

	//alert('추가');
	addCategoryModal
	$('#addCategoryModal').modal({
		escapeClose: false,
		clickClose: false,
		showClose: true, 
		fadeDuration: 100
	});
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



