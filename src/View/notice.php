<!-- 비주얼 영역 -->
<div class="visual visual--sub">
    <div class="background background--black">
        <img src="/images/visual/sub.jpg" alt="배경 이미지" title="배경 이미지">
    </div>
    <div class="position-center text-center mt-4 w-100">
        <div class="fx-7 text-white font-weight-bold mb-3">알려드립니다</div>
        <div class="fx-n1 text-gray">축제 공지사항 > 알려드립니다</div>
    </div>
</div>
<!-- /비주얼 영역 -->

<div class="container">
    <div class="py-5">
        <div class="d-between">
            <div>
                <hr>
                <div class="title">공지사항 - <?= $notice->id ?></div>
            </div>
            <?php if(admin()):?>
            <div>
                <button class="btn-filled" data-toggle="modal" data-target="#edit-modal">수정하기</button>
                <a href="/delete/notices/<?=$notice->id?>" class="btn-bordered">삭제하기</a>
            </div>
            <?php endif;?>
        </div>
        <div class="fx-4 py-3"><?= enc($notice->title) ?></div>
        <div class="fx-n1 py-2">작성일: <?= dt($notice->created_at) ?></div>
        <div class="fx-2 text-muted py-4"><?= enc($notice->content) ?></div>
        <div class="row">
            <?php foreach($notice->files as $file):?>
                <?php if(array_search($file->extname, [".jpg", ".gif", ".png"]) !== false):?>
                    <div class="col-lg-6 mb-4">
                        <img src="<?= $file->viewPath ?>" alt="이미지" class="w-100">
                    </div>
                <?php endif;?>
            <?php endforeach;?>
        </div>
    </div>
    <div class="py-5 border-top">
        <hr>
        <div class="title">파일</div>
        <div class="border-top mt-4">
            <?php foreach($notice->files as $file):?>
            <div class="d-between border-bottom py-3">
                <div>
                    <span class="fx-2"><?= $file->name ?></span>
                    <span class="ml-1 badge badge-danger"><?= $file->fileSize ?></span>
                </div>
                <a href="/download/<?=$file->filename?>" class="btn-bordered">다운로드</a>
            </div>
            <?php endforeach;?>
        </div>
    </div>
</div>

<form action="/update/notices/<?=$notice->id?>" method="post" enctype="multipart/form-data" id="edit-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="fx-4">수정하기</div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>제목</label>
                    <input type="text" class="form-control" name="title" maxlength="50" value="<?=$notice->title?>" required>
                </div>
                <div class="form-group">
                    <label>내용</label>
                    <textarea class="form-control" name="content" id="content" cols="30" rows="10" required><?=$notice->content?></textarea>
                </div>
                <div class="form-group">
                    <label>첨부 파일</label>
                    <div class="custom-file">
                        <label for="files" class="custom-file-label"><?= count($notice->files) > 0 ? count($notice->files) . "개의 파일" : "파일을 선택하세요" ?></label>
                        <input type="file" id="files" class="custom-file-input" name="files[]" multiple>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-filled">수정 완료</button>
            </div>
        </div>
    </div>
</form>