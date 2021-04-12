$(document).ready(function() {	
	summernote_init();

	form_init();
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

	const bv_categoryIdList = new  BVSelect({
		selector: '#category',
		searchbox: true,
        offset: true, 
		width: '100%'
	});

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

	alert('추가');
}
