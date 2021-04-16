    </div> <!-- end s-wrap -->

    <!-- Java Script ================================================== -->
    <script src="/public/template/js/jquery-3.2.1.min.js"></script>
    <script src="/public/template/js/plugins.js"></script>
    <script src="/public/template/js/main.js"></script>

    <script src="/public/js/common.js"></script>


    <?php if ($is_write) { ?>

    	<!-- include codemirror (codemirror.css, codemirror.js, xml.js, formatting.js) use summernote codeview -->
    	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.css">
    	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/theme/monokai.css">
    	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.js"></script>
    	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/mode/xml/xml.js"></script>
    	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/2.36.0/formatting.js"></script>

    	<!-- include summernote js -->
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.js"></script>
    	<!-- include summernote-ko-KR -->
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/lang/summernote-ko-KR.js"></script>

    	<!-- include summernote plugins - add-text-tags -->
    	<link rel="stylesheet" type="text/css" href="/public/vendor/summernote/add-text-tags/summernote-add-text-tags.css">
    	<script src="/public/vendor/summernote/add-text-tags/summernote-add-text-tags.js"></script>

    	<!-- include summernote plugins - emoji -->
    	<script src="https://use.fontawesome.com/52e183519a.js"></script>
    	<link rel="stylesheet" type="text/css" href="/public/vendor/summernote/emoji/tam-emoji/css/emoji.css" rel="stylesheet">
    	<script src="/public/vendor/summernote/emoji/tam-emoji/js/config.js"></script>
    	<script src="/public/vendor/summernote/emoji/tam-emoji/js/tam-emoji.min.js?v=1.1"></script>

    	<!-- include bvselect (jquery plugin) -->
    	<link rel="stylesheet" type="text/css" href="/public/vendor/bvselect/css/bvselect.css" rel="stylesheet">
    	<script src="/public/vendor/bvselect/js/bvselect.js"></script>

    	<!-- jQuery Modal -->
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
		
    	<script src="/public/js/write.js"></script>
    <?php } ?>

    </body>

    </html>
