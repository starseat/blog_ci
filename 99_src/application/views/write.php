<!-- TOAST UI Editor -->
<link rel="stylesheet" href="https://uicdn.toast.com/editor/3.1.2/toastui-editor.css" />

<!-- TOAST UI Editor's Plugin -->
<link rel="stylesheet" href="https://uicdn.toast.com/tui-color-picker/v2.2.6/tui-color-picker.css" />
<link rel="stylesheet" href="https://uicdn.toast.com/editor-plugin-color-syntax/3.0.1/toastui-editor-plugin-color-syntax.min.css" />
<!-- TOAST UI Editor's Plugin - Prismjs Languages -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.23.0/themes/prism.min.css" />
<link rel="stylesheet" href="https://uicdn.toast.com/editor-plugin-code-syntax-highlight/3.0.0/toastui-editor-plugin-code-syntax-highlight.min.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.33.0/codemirror.css" />
<link rel="stylesheet" href="https://uicdn.toast.com/editor-plugin-table-merged-cell/3.0.0/toastui-editor-plugin-table-merged-cell.min.css" />

<!-- TOAST UI Editor's Plugin - Chart -->
<link rel="stylesheet" href="https://uicdn.toast.com/chart/v4.1.4/toastui-chart.min.css" />


<!-- jQuery Modal / https://www.npmjs.com/package/jquery-modal -->
<!-- https://stove99.github.io/javascript/2019/04/16/jquery-modal-plugin/ -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
<link rel="stylesheet" href="/public/css/write.css" />

<div id="top" class="s-wrap site-wrapper">

	<div class="s-content content" id="write-content">
		<main class="row s-styles">
			<div class="column tab-full">
				<h1 class="display-1">
					<?php
					if ($is_modify) {
						echo '게시글 수정';
					} else {
						echo '새 글 작성';
					}
					?>
				</h1>
				<div style="text-align: right;">
					<cite><span id="writer">By <?= $this->session->userdata['name']; ?></span></cite>
				</div>

				<br>

				<!-- <form action="#" method="post" enctype="multipart/form-data" id="writeForm" name="writeForm"> -->
				<?php
				// csrf 
				$attributes = array(
					'id' => 'writeForm',
					'name' => 'writeForm',
					'enctype' => 'multipart/form-data'
				);

				if ($is_modify) {
					echo form_open('/write/update', $attributes);
				} else {
					echo form_open('/write/insert', $attributes);
				}
				?>
				<div class="row">
					<div class="column large-6 tab-full">
						<label for="blog_category">Category</label>
						<select name="blog_category" id="blog_category" class="full-width cursor-pointer">
							<?php
							foreach ($categories as $category) {
								if (count($category['children']) > 0) {
							?>
									<optgroup label="<?= $category['category_name'] ?>">
										<?php
										foreach ($category['children'] as $child) {
										?>
											<option value="<?= $child['category_id'] ?>">&nbsp;&nbsp;<?= $child['category_name'] ?></option>
										<?php
										} // end of foreach($category['children'] as $child)
										?>
									</optgroup>

								<?php
								} else {
								?>
									<option value="<?= $category['category_id'] ?>"><?= $category['category_name'] ?></option>
							<?php
								}
							} // end of foreach ($categories as $category)
							?>
						</select>
					</div>

					<?php if (!$is_modify) { ?>
						<div class="column large-4 tab-full">
							<label for="addCategory">카테고리 추가</label>
							<!-- <button class="btn btn--stroke" id="addCategory" onclick="showAddCategoryModal(event)">+</button> -->
							<button class="pgn__num" id="addCategory" onclick="showAddCategoryModal(event)">+</button>

							&nbsp;<small id="category_comment">(카테고리 추가시 Contents 내용은 지워집니다.)</small>
						</div>
					<?php } ?>

					<div class="column large-2 tab-full">
						<label for="blog_writeType_html">작성 타입</label>
						<div>
							<label class="radio-inline">
								<input type="radio" name="blog_writeType" class="blog_writeType" id="blog_writeType_html" value="html" checked> html
							</label>
							<label class="radio-inline">
								<input type="radio" name="blog_writeType" class="blog_writeType" id="blog_writeType_md" value="md"> md
							</label>
						</div>
					</div>
				</div>

				<br>

				<div class="row">
					<div class="column large-6 tab-full">
						<label for="blog_title">Title</label>
						<input class="full-width" type="text" placeholder="글 제목을 입력해 주세요." id="blog_title" name="blog_title">
					</div>

					<div class="column large-4 tab-full">
						<label for="blog_viewType">보기 설정</label>
						<?php if ($is_modify) { ?>
							<div class="ss-custom-select">
								<select class="full-width cursor-pointer" id="blog_viewType" name="blog_viewType">
									<option value="<?= Category_model::VIEW_TYPE_ALL ?>">전체보기</option>
									<!-- <option value="<?= Category_model::VIEW_TYPE_FRIEND ?>">친구만 보기</option> -->
									<option value="<?= Category_model::VIEW_TYPE_ONLY_ME ?>">나만보기</option>
									<!-- <option value="<?= Category_model::VIEW_TYPE_ADMIN ?>">관리자용</option> -->
								</select>
							</div>
						<?php } else { ?>
							<input class="full-width" type="hidden" id="blog_viewType" name="blog_viewType" value="<?= Category_model::VIEW_TYPE_ONLY_ME ?>">
							<input class="full-width" type="text" id="blog_viewType_view_text" value="작성중인 포스트" readonly>
						<?php } ?>
					</div>

					<div class="column large-2 tab-full">
						<label for="blog_thumbnail">썸네일</label>
						<div class="thumbnail_box" style="width: 64px; height: 64px; margin: 0 auto;">
							<input type="file" id="blog_thumbnail" name="blog_thumbnail" style="display: none;" accept="image/*" capture="gallery" onchange="loadImage(this);">
							<img id="blog_thumbnail_temp" class="cursor-pointer" src="/public/imgs/thumbnail_box.svg" alt="thumbnail image" onclick='document.all.blog_thumbnail.click();'>
						</div>

					</div>
				</div>

				<div class="row" style="padding-left: 20px; padding-right: 20px;">
					<label for="blog_content_view">Contents</label>
				</div>
				<div class="row" style="padding-left: 20px; padding-right: 20px;">
					<div id="blog_content_view" class="full-width"></div>
					<textarea id="blog_content" name="blog_content" style="display: none;">
					<?php
					if ($is_modify) {
						echo $board_data['content'];
					}
					?>
					</textarea>

					<br>
				</div>

				<?php if ($is_modify) { ?>
					<input type="hidden" id="saved_blog_category" value="<?= $board_data['category_id']; ?>" />
					<input type="hidden" id="saved_blog_title" value="<?= $board_data['title']; ?>" />
					<input type="hidden" id="saved_blog_view_type" value="<?= $board_data['view_type']; ?>" />
					<input type="hidden" id="saved_blog_write_type" value="<?= $board_data['write_type']; ?>" />
					<input type="hidden" id="saved_blog_thumbnail" value="<?= $board_data['thumbnail']; ?>" />
					<input type="hidden" id="saved_blog_seq" name="blog_seq" value="<?= $board_data['seq']; ?>" />
					<input type="hidden" name="blog_writer" value="<?= $board_data['writer']; ?>" />
				<?php } else { ?>
					<input type="hidden" id="saved_blog_seq" name="blog_seq" value="0" />
				<?php } ?>

				<?php
				// config.php 의 $config['csrf_protection'] = FALSE; 로 설정되어 있을경우 아래와 같이 사용
				$csrf = array(
					'name' => $this->security->get_csrf_token_name(),
					'hash' => $this->security->get_csrf_hash()
				);
				?>
				<input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />

				<br>

				<div class="row">
					<div class="column large-3 tab-full">
						<?php if ($is_modify) { ?>
							<button class="btn full-width" onclick="exitWritePage(event, '<?= $board_data['category_id']; ?>');">목록</button>
						<?php } else { ?>
							<button class="btn full-width" onclick="exitWritePage(event);">나가기</button>
						<?php } ?>

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

	<?php if (!$is_modify) { ?>
		<div id="addCategoryModal" class="modal">
			<div class="row s-styles">
				<div class="column tab-full">
					<?php
					$attributes = array(
						'id' => 'addNewCategoryForm',
						'name' => 'addNewCategoryForm',
						'style' => 'width: 100%;'
					);

					$hidden = array(
						'addCategoryModal_boardSeq' => 0
					);

					echo form_open('/write/addCategory', $attributes, $hidden);
					?>
					<div class="row">
						<label for="addCategoryModal_newParent">상위 카테고리</label>
						<select name="addCategoryModal_newParent" id="addCategoryModal_newParent" class="full-width cursor-pointer">
							<option value="0">없음 (선택시 상위 카테고리로 생성됩니다.)</option>
							<?php
							foreach ($categories as $category) {
								if (intval($category['level']) == 0) {
							?>
									<option value="<?= $category['category_id'] ?>"><?= $category['category_name'] ?></option>
							<?php
								}
							} // end of foreach ($categories as $category)
							?>
						</select>
					</div>
					<div class="row">
						<div class="column large-6 tab-full padding-0 padding-right-10">
							<label for="addCategoryModal_newCategoryId">새 카테고리 ID</label>
							<input class="full-width" type="text" placeholder="새 카테고리 ID 를 입력해 주세요." id="addCategoryModal_newCategoryId" name="addCategoryModal_newCategoryId">
						</div>
						<div class="column large-6 tab-full padding-0 padding-left-10">
							<label for="addCategoryModal_newCategoryViewType">보기 설정</label>
							<select class="full-width cursor-pointer" id="addCategoryModal_newCategoryViewType" name="addCategoryModal_newCategoryViewType">
								<option value="0">전체보기</option>
								<!-- <option value="1">친구만 보기</option> -->
								<option value="2">나만보기</option>
								<!-- <option value="9">관리자용</option> -->
							</select>
						</div>
					</div>
					<div class="row">
						<label for="addCategoryModal_newCategoryName">새 카테고리명</label>
						<input class="full-width" type="text" placeholder="새 카테고리명을 입력해 주세요." id="addCategoryModal_newCategoryName" name="addCategoryModal_newCategoryName">
					</div>
					</form>
				</div>

			</div>

			<div class="addCategoryModal_bottom_box row">
				<a href="#" rel="modal:close" class="addCategoryModal_bottom_close">Close</a>
				<a href="javascript:void(0)" rel="add new category" id="addNewCategorySubmit">Submit</a>
			</div>
		</div>
	<?php } ?>


	<!-- TOAST UI Editor -->
	<!-- https://github.com/nhn/tui.editor/tree/v3.0.0 -->
	<script src="https://uicdn.toast.com/editor/3.1.2/toastui-editor-all.min.js"></script>
	<script src="https://uicdn.toast.com/editor/3.1.2/i18n/ko-kr.js"></script>

	<!-- TOAST UI Editor's Plugin -->
	<!-- https://github.com/nhn/tui.editor/tree/v3.0.0/plugins -->

	<script src="https://uicdn.toast.com/tui-color-picker/v2.2.6/tui-color-picker.min.js"></script>
	<script src="https://uicdn.toast.com/editor-plugin-color-syntax/latest/toastui-editor-plugin-color-syntax.min.js"></script>

	<!-- TOAST UI Editor's Plugin - Prismjs Languages -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.23.0/prism.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.23.0/components/prism-clojure.min.js"></script>
	<script src="https://uicdn.toast.com/editor-plugin-code-syntax-highlight/3.0.0/toastui-editor-plugin-code-syntax-highlight.min.js"></script>

	<script src="https://uicdn.toast.com/editor-plugin-table-merged-cell/3.0.0/toastui-editor-plugin-table-merged-cell.min.js"></script>
	<script src="https://uicdn.toast.com/editor-plugin-uml/3.0.1/toastui-editor-plugin-uml.min.js"></script>

	<!-- TOAST UI Editor's Plugin - Chart -->
	<script src="https://uicdn.toast.com/chart/v4.1.4/toastui-chart.js"></script>
	<script src="https://uicdn.toast.com/editor-plugin-chart/3.0.1/toastui-editor-plugin-chart.min.js"></script>


	<!-- jQuery Modal -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
	<script src="/public/js/write.js"></script>
