<!-- 비주얼 영역 -->
<div class="visual visual--sub">
    <div class="background background--black">
        <img src="/images/visual/sub.jpg" alt="배경 이미지" title="배경 이미지">
    </div>
    <div class="position-center text-center mt-4 w-100">
        <div class="fx-7 text-white font-weight-bold mb-3">1:1문의</div>
        <div class="fx-n1 text-gray">축제 공지사항 > 1:1문의</div>
    </div>
</div>
<!-- /비주얼 영역 -->

<div class="container py-5">
    <div class="d-between">
        <div>
            <hr class="bg-red">
            <div class="title text-red">1:1문의</div>
        </div>
        <button class="btn-filled" data-toggle="modal" data-target="#insert-modal">문의하기</button>
    </div>
    <div class="t-head">
        <div class="cell-10">상태</div>
        <div class="cell-70">제목</div>
        <div class="cell-20">문의 일자</div>
    </div>
    <?php foreach($inquires as $inquire):?>
        <div class="t-row" data-toggle="modal" data-target="#view-modal-<?=$inquire->id?>">
            <div class="cell-10"><?= $inquire->answered ? "완료" : "진행 중" ?></div>
            <div class="cell-70"><?= enc($inquire->title) ?></div>
            <div class="cell-20"><?= dt($inquire->created_at) ?></div>
        </div>
        <div id="view-modal-<?=$inquire->id?>" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="fx-4">문의 내역</div>
                    </div>
                    <div class="modal-body">
                        <div class="py-2">
                            <div class="fx-4 mb-2"><?= enc($inquire->title) ?></div>
                            <div class="fx-n1 text-muted mb-3"><?= $inquire->user_name ?>(<?=$inquire->user_email?>)</div>
                            <div class="fx-n1 text-muted mb-3"><?= dt($inquire->created_at) ?></div>
                            <div class="fx-2"><?= enc($inquire->content) ?></div>
                        </div>
                        <div class="border-top py-2">
                            <?php if($inquire->answered):?>
                                <div class="fx-n1 text-muted"><?=dt($inquire->answered_at)?></div>
                                <div class="fx-2"><?=enc($inquire->answer)?></div>
                            <?php else:?>
                                <div class="fx-n2 text-muted">문의에 대한 답변이 오지 않았습니다.</div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach;?>
</div>

<form action="/insert/inquires" method="post" id="insert-modal" class="fade modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="fx-4">문의하기</div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>제목</label>
                    <input type="text" class="form-control" name="title" maxlength="50" required>
                </div>
                <div class="form-group">
                    <label>내용</label>
                    <textarea name="content" id="content" cols="30" rows="10" class="form-control" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-filled">작성 완료</button>
            </div>
        </div>
    </div>
</form>