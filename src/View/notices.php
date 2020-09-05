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

<div class="container py-5">
    <div class="d-between">
        <div>
            <hr class="bg-red">
            <div class="title text-red">알려드립니다</div>
        </div>
        <?php if(admin()):?>
            <button class="btn-filled" data-toggle="modal" data-target="#add-modal">공지 작성</button>
        <?php endif;?>
    </div>
    <div class="mt-3 t-head">
        <div class="cell-10">글 번호</div>
        <div class="cell-70">제목</div>
        <div class="cell-20">작성일자</div>
    </div>
    <?php foreach($notices->data as $notice):?>
        <div class="t-row" onclick="location.href='/notices/<?=$notice->id?>';">
            <div class="cell-10"><?=$notice->id?></div>
            <div class="cell-70 text-left text-ellipsis"><?= enc($notice->title); ?> </div>
            <div class="cell-20"><?= dt($notice->created_at) ?></div>
        </div>
    <?php endforeach;?>
    <div class="mt-4 d-center">
        <a href="/notices?page=<?=$notices->prevPage?>" class="icon bg-red text-white" <?=$notices->prev ? "" : "disabled"?>>
            <i class="fa fa-angle-left"></i>
        </a>
        <?php for($i = $notices->start; $i <= $notices->end; $i++):?>
            <a href="/notices?page=<?=$i?>" class="icon <?= $i == $notices->page ? "bg-red text-white"  : "border text-muted" ?>"><?=$i?></a>
        <?php endfor;?>
        <a href="/notices?page=<?=$notices->nextPage?>" class="icon bg-red text-white" <?=$notices->next ? "" : "disabled"?>>
            <i class="fa fa-angle-right"></i>
        </a>
    </div>
</div>

<form action="/insert/notices" id="add-modal" class="modal fade" method="post" enctype="multipart/form-data">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="fx-4">공지 작성</div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>제목</label>
                    <input type="text" class="form-control" name="title" maxlength="50" required>
                </div>
                <div class="form-group">
                    <label>내용</label>
                    <textarea name="content" id="content" cols="30" rows="3" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label>첨부 파일</label>
                    <div class="custom-file">
                        <label id="files" class="custom-file-label">파일을 선택하세요</label>
                        <input type="file" id="files" class="custom-file-input" name="files[]" multiple>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-filled">작성 완료</button>
            </div>
        </div>
    </div>
</form>
