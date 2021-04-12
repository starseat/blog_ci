<!-- include codemirror (codemirror.css, codemirror.js, xml.js, formatting.js) -->
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.css">
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/theme/monokai.css">
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/mode/xml/xml.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/2.36.0/formatting.js"></script>

<!-- include summernote css -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.css" rel="stylesheet">

<!-- include bvselect css (jquery plugin) -->
<link rel="stylesheet" href="/public/vendor/bvselect/css/bvselect.css">

<style>
	#write-content {
		padding: 60px;
		margin: 0 auto;
	}

	li.nofocus {
		height: 38px;
	}

	#writer {
		color: #33998a;
		transition: all 0.3s ease-in-out;
	}

	#addCategory {
		height: 42px;
		width: 42px;
		margin-top: 10px;
		margin-bottom: 10px;
		padding-top: 4px;
		padding-bottom: 4px;
	}

	#category_comment {
		display: inline-block;
		font-family: "IBM Plex Sans", sans-serif;
		font-weight: 400;
		font-size: 1.6rem;
		line-height: 1.556;
		color: #7e7e7e;
	}
</style>
<div id="top" class="s-wrap site-wrapper">

	<div class="s-content content" id="write-content">
		<main class="row s-styles">
			<div class="column tab-full">
				<h1 class="display-1">새 글 작성</h1>
				<div style="text-align: right;">
					<cite><span id="writer">By ---로그인 ID---</span></cite>
				</div>

				<br>

				<form action="#" method="post" enctype="multipart/form-data" id="writeForm" name="writeForm">
					<div class="row">
						<div class="column large-6 tab-full">
							<label for="category">Category</label>
							<select name="category" id="category">
								<?php
								foreach ($categories as $category) {
									if (isset($category['children']) && !is_null($category['children']) && count($category['children']) > 0) {
								?>
										<li class="has-children">
											<option value="##" data-separator="true"><?= $category['category_name'] ?></option>
											<?php
											foreach ($category['children'] as $child) {
											?>
												<option value="<?= $child['category_id'] ?>">&nbsp;&nbsp;<?= $child['category_name'] ?></option>
											<?php
											} // end of foreach($category['children'] as $child)
											?>
										<?php
									} else {
										?>
											<option value="<?= $child['category_id'] ?>"><?= $child['category_name'] ?></option>
									<?php
									}
								}
									?>
							</select>
						</div>

						<div class="column large-6 tab-full">
							<label for="addCategory">카테고리 추가</label>
							<!-- <button class="btn btn--stroke" id="addCategory" onclick="showAddCategoryModal(event)">+</button> -->
							<button class="pgn__num" id="addCategory" onclick="showAddCategoryModal(event)">+</button>

							&nbsp;<small id="category_comment">(카테고리 추가시 Contents 내용은 지워집니다.)</small>
						</div>
					</div>

					<br>

					<div class="row">
						<div class="column large-6 tab-full">
							<label for="title">Title</label>
							<input class="full-width" type="text" placeholder="글 제목을 입력해 주세요." id="title">
						</div>

						<div class="column large-4 tab-full">
							<label for="viewType">보기 설정</label>
							<div class="ss-custom-select">
								<select class="full-width" id="viewType">
									<option value="0">전체보기</option>
									<option value="1">친구만 보기</option>
									<option value="2">나만보기</option>
									<option value="9">관리자용</option>
								</select>
							</div>
						</div>

						<div class="column large-2 tab-full">
							<label for="thumbnail">썸네일</label>
							<div class="thumbnail_box" style="width: 64px; height: 64px; margin: 0 auto;">
								<input type="file" id="thumbnail" name="thumbnail" style="display: none;" accept="image/*" capture="gallery" onchange="loadImage(this);">
								<img id="thumbnail_temp" src="/public/imgs/thumbnail_box.svg" alt="thumbnail image" onclick='document.all.thumbnail.click();'>
							</div>

						</div>
					</div>

					<div class="row" style="padding-left: 20px; padding-right: 20px;">
						<label for="content">contents</label>
						<div id="content"></div>
					</div>

					<br>

					<div class="row">
						<div class="column large-3 tab-full">
							<button class="btn full-width" onclick="javascript: location.history.go(-1); ">나가기</button>
						</div>
						<div class="column large-3 tab-full"></div>
						<div class="column large-6 tab-full">
							<button class="btn btn--stroke full-width" onclick="submitBlog(event)">등록</button>
						</div>
					</div>

				</form>

			</div> <!-- end of .column.tab-full -->
		</main>

	</div>
