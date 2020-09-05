<!-- 비주얼 영역 -->
<div class="visual visual--sub">
    <div class="background background--black">
        <img src="/images/visual/sub.jpg" alt="배경 이미지" title="배경 이미지">
    </div>
    <div class="position-center text-center mt-4 w-100">
        <div class="fx-7 text-white font-weight-bold mb-3">참가작품</div>
        <div class="fx-n1 text-gray">한지공예대전 > 참가작품</div>
    </div>
</div>
<!-- /비주얼 영역 -->


<div class="container py-5">
    <div class="row">
        <div class="col-lg-5">
            <img src="/uploads/<?=$artwork->image?>" alt="이미지" class="fit-cover hx-200">
        </div>
        <div class="col-lg-7">    
            <div class="fx-3 mb-2"><?=enc($artwork->title)?></div>
            <div class="mt-2">
                <span class="fx-n2 text-muted">제작일자</span>
                <span class="fx-n1 ml-2"><?= dt($artwork->created_at) ?></span>
            </div>
            <div class="mt-2">
                <span class="fx-n2 text-muted">평점</span>
                <span class="fx-n1 ml-2"><?= $artwork->score ?></span>
            </div>
            <div class="mt-2 fx-n2 text-muted d-flex flex-wrap mb-4">
                <?php foreach($artwork->hash_tags as $tag):?>
                    <span class="m-1">#<?=$tag?></span>
                <?php endforeach;?>
            </div>
            <div class="fx-2"><?=  enc($artwork->content) ?></div>
            <?php if(user() && user()->id == $artwork->uid):?>
                <div class="mt-3">
                    <button data-target="#update-modal" data-toggle="modal" class="btn-filled">수정하기</button>
                    <a href="/delete/artworks/<?=$artwork->id?>" class="btn-bordered">삭제하기</a>
                </div>
            <?php elseif(admin()):?>
                <div class="mt-3">
                    <button class="btn-filled" data-target="#delete-modal" data-toggle="modal">삭제하기</button>
                </div>
            <?php endif;?>
        </div>
    </div>
</div>

<?php if(user() && !$artwork->reviewed && user()->id != $artwork->uid):?>
<div class="container py-5">
    <form method="post" action="/insert/scores" class="p-4 border bg-white align-center">
        <input type="hidden" name="aid" value="<?=$artwork->id?>">
        <select name="score" id="score" class="form-control fa" style="width: 150px">
            <option value="5"><?= str_repeat("&#xf005;", 5) ?></option>
            <option value="4.5"><?= str_repeat("&#xf005;", 4) ?>&#xf123;</option>
            <option value="4"><?= str_repeat("&#xf005;", 4) ?></option>
            <option value="3.5"><?= str_repeat("&#xf005;", 3) ?>&#xf123;</option>
            <option value="3"><?= str_repeat("&#xf005;", 3) ?></option>
            <option value="2.5"><?= str_repeat("&#xf005;", 2) ?>&#xf123;</option>
            <option value="2"><?= str_repeat("&#xf005;", 2) ?></option>
            <option value="1.5"><?= str_repeat("&#xf005;", 1) ?>&#xf123;</option>
            <option value="1"><?= str_repeat("&#xf005;", 1) ?></option>
            <option value="0.5"><?= str_repeat("&#xf005;", 0) ?>&#xf123;</option>
        </select>
        <button class="btn-filled ml-3">확인</button>
    </form>
</div>
<?php endif;?>

<div class="container py-5">
    <div class="border bg-white p-3 align-center">
        <img src="/uploads/<?=$artwork->user_image?>" alt="이미지" width="80" height="80">
        <div class="ml-4">
            <div>
                <span class="fx-3"><?=$artwork->user_name?></span>
                <span class="badge badge-primary"><?= $artwork->type === 'company' ? '기업' : '일반' ?></span>
            </div>
            <div class="fx-n2 text-muted"><?= $artwork->user_email ?></div>
        </div>
    </div>
</div>


<form action="/update/artworks/<?=$artwork->id?>" method="post" id="update-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="fx-4">수정하기</div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>제목</label>
                    <input type="text" name="title" class="form-control" value="<?=$artwork->title?>" required>
                </div>
                <div class="form-group">
                    <label>설명</label>
                    <textarea name="content" id="content" cols="30" rows="10" class="form-control" required><?=$artwork->content?></textarea>
                </div>
                <div class="form-group">
                    <label>해시 태그</label>
                    <div id="update-module" data-name="hash_tags"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-filled">수정 완료</button>
            </div>
        </div>
    </div>
</form>
<script>
    let module = new HashModule("#update-module");
    module.tags = <?= json_encode($artwork->hash_tags) ?>;
    module.update();
</script>
<form action="/delete-admin/artworks/<?=$artwork->id?>" id="delete-modal" class="modal fade" method="post">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="fx-4">삭제하기</div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>삭제 사유</label>
                    <input type="text" name="rm_reason" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-filled">삭제 완료</button>
            </div>
        </div>
    </div>
</form>


