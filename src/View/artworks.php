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

<!-- 검색 영역 -->
<div class="container py-5">
    <form class="p-3 border bg-light d-center">
        <div id="search-module" class="w-100 mr-4" data-name="search"></div>
        <button class="btn-search icon text-red"><i class="fa fa-search"></i></button>
    </form>
</div>
<!-- /검색 영역 -->
<script>
    let module = new HashModule("#search-module", <?= json_encode($tags) ?>);
    module.tags = <?= json_encode($search) ?>;
    module.update();
</script>

<?php if(user()):?>
<div class="container py-5">
    <div class="pb-4 mb-4 border-bottom">
        <hr>
        <div class="title">등록한 작품</div>
    </div>
    <div class="row">
        <?php foreach($myList as $artwork):?>
        <div class="col-lg-3 mb-4">
            <div class="bg-white border" onclick="location.href='/artworks/<?=$artwork->id?>'" <?= $artwork->rm_reason ? 'disabled' : '' ?>>
                <img src="/uploads/<?=$artwork->image?>" alt="작품 이미지" class="fit-cover hx-200">
                <div class="p-3 border-top">
                    <div>
                        <span class="fx-3"><?= enc($artwork->title) ?></span>
                    </div>
                    <div class="mt-2">
                        <span class="fx-n2 text-muted">제작자</span>
                        <span class="fx-n1 ml-2"><?= $artwork->user_name ?></span>
                        <span class="badge badge-danger"><?= $artwork->type == 'company' ? '기업' : '일반' ?></span>
                    </div>
                    <div class="mt-2">
                        <span class="fx-n2 text-muted">제작일자</span>
                        <span class="fx-n1 ml-2"><?= dt($artwork->created_at) ?></span>
                    </div>
                    <div class="mt-2">
                        <span class="fx-n2 text-muted">평점</span>
                        <span class="fx-n1 ml-2"><?= $artwork->score ?></span>
                    </div>
                    <div class="mt-1 d-flex flex-wrap fx-n2 text-muted">
                        <?php foreach($artwork->hash_tags as $tag): ?>
                            <div class="p-1">#<?=$tag?></div>
                        <?php endforeach;?>
                    </div>
                </div>  
                <?php if($artwork->rm_reason):?>
                    <div class="p-3 border-top">
                        <div class="fx-n2 text-muted">삭제 사유</div>
                        <div class="mt-1 fx-n1"><?= enc($artwork->rm_reason) ?></div>
                    </div>
                <?php endif;?>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</div>
<?php endif;?>


<div class="container py-5">
    <div class="pb-4 mb-4 border-bottom">
        <hr>
        <div class="title">우수 작품</div>
    </div>
    <div class="row">
        <?php foreach($rankers as $artwork):?>
        <div class="col-lg-3 mb-4">
            <div class="bg-white border" onclick="location.href='/artworks/<?=$artwork->id?>'">
                <img src="/uploads/<?=$artwork->image?>" alt="작품 이미지" class="fit-cover hx-200">
                <div class="p-3 border-top">
                    <div>
                        <span class="fx-3"><?= enc($artwork->title) ?></span>
                        <span class="badge badge-primary">우수작품</span>
                    </div>
                    <div class="mt-2">
                        <span class="fx-n2 text-muted">제작자</span>
                        <span class="fx-n1 ml-2"><?= $artwork->user_name ?></span>
                        <span class="badge badge-danger"><?= $artwork->type == 'company' ? '기업' : '일반' ?></span>
                    </div>
                    <div class="mt-2">
                        <span class="fx-n2 text-muted">제작일자</span>
                        <span class="fx-n1 ml-2"><?= dt($artwork->created_at) ?></span>
                    </div>
                    <div class="mt-2">
                        <span class="fx-n2 text-muted">평점</span>
                        <span class="fx-n1 ml-2"><?= $artwork->score ?></span>
                    </div>
                    <div class="mt-1 d-flex flex-wrap fx-n2 text-muted">
                        <?php foreach($artwork->hash_tags as $tag): ?>
                            <div class="p-1">#<?=$tag?></div>
                        <?php endforeach;?>
                    </div>
                </div>  
            </div>
        </div>
        <?php endforeach;?>
    </div>
</div>

<div class="container py-5">
    <div class="pb-4 mb-4 border-bottom">
        <hr>
        <div class="title">모든 작품</div>
    </div>
    <div class="row">
        <?php foreach($artworks->data as $artwork):?>
        <div class="col-lg-3 mb-4">
            <div class="bg-white border" onclick="location.href='/artworks/<?=$artwork->id?>'">
                <img src="/uploads/<?=$artwork->image?>" alt="작품 이미지" class="fit-cover hx-200">
                <div class="p-3 border-top">
                    <div>
                        <span class="fx-3"><?= enc($artwork->title) ?></span>
                    </div>
                    <div class="mt-2">
                        <span class="fx-n2 text-muted">제작자</span>
                        <span class="fx-n1 ml-2"><?= $artwork->user_name ?></span>
                        <span class="badge badge-danger"><?= $artwork->type == 'company' ? '기업' : '일반' ?></span>
                    </div>
                    <div class="mt-2">
                        <span class="fx-n2 text-muted">제작일자</span>
                        <span class="fx-n1 ml-2"><?= dt($artwork->created_at) ?></span>
                    </div>
                    <div class="mt-2">
                        <span class="fx-n2 text-muted">평점</span>
                        <span class="fx-n1 ml-2"><?= $artwork->score ?></span>
                    </div>
                    <div class="mt-1 d-flex flex-wrap fx-n2 text-muted">
                        <?php foreach($artwork->hash_tags as $tag): ?>
                            <div class="p-1">#<?=$tag?></div>
                        <?php endforeach;?>
                    </div>
                </div>  
            </div>
        </div>
        <?php endforeach;?>
    </div>
    <div class="mt-4 d-center">
        <a href="/artworks?page=<?=$artworks->prevPage?>" class="icon bg-red text-white" <?=$artworks->prev ? "" : "disabled"?>>
            <i class="fa fa-angle-left"></i>
        </a>
        <?php for($i = $artworks->start; $i <= $artworks->end; $i++):?>
            <a href="/artworks?page=<?=$i?>" class="icon <?= $i == $artworks->page ? "bg-red text-white"  : "border text-muted" ?>"><?=$i?></a>
        <?php endfor;?>
        <a href="/artworks?page=<?=$artworks->nextPage?>" class="icon bg-red text-white" <?=$artworks->next ? "" : "disabled"?>>
            <i class="fa fa-angle-right"></i>
        </a>
    </div>
</div>