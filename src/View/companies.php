<!-- 비주얼 영역 -->
<div class="visual visual--sub">
    <div class="background background--black">
        <img src="/images/visual/sub.jpg" alt="배경 이미지" title="배경 이미지">
    </div>
    <div class="position-center text-center mt-4 w-100">
        <div class="fx-7 text-white font-weight-bold mb-3">온라인스토어</div>
        <div class="fx-n1 text-gray">한지상품판매관 > 온라인스토어</div>
    </div>
</div>
<!-- /비주얼 영역 -->

<!-- 우수 업체 -->
<div class="container py-5">
    <div class="border-bottom pb-4 mb-4">
        <hr>
        <div class="title">우수 업체</div>
    </div>
    <div class="row">
        <?php foreach($rankers as $company):?>
            <div class="col-lg-3">
                <div class="border bg-white">
                    <img src="/uploads/<?=$company->image?>" alt="기업 이미지" class="fit-cover hx-200">
                    <div class="p-3 border-top">
                        <div>
                            <span class="fx-3"><?= $company->user_name ?></span>
                            <span class="badge badge-primary">우수 업체</span>
                        </div>
                        <div class="mt-2">
                            <span class="fx-n2 text-muted">이메일</span>
                            <span class="fx-n1 ml-2"><?= $company->user_email ?></span>
                        </div>
                        <div class="mt-2">
                            <span class="fx-n2 text-muted">누적 포인트</span>
                            <span class="fx-n1 ml-2"><?= $company->totalPoint ?>p</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>
<!-- /우수 업체 -->

<!-- 모든 업체 -->
<div class="container py-5">
    <div class="border-bottom pb-4 mb-4">
        <hr>
        <div class="title">모든 업체</div>
    </div>
    <div class="row">
        <?php foreach($companies->data as $company):?>
            <div class="col-lg-3">
                <div class="border bg-white">
                    <img src="/uploads/<?=$company->image?>" alt="기업 이미지" class="fit-cover hx-200">
                    <div class="p-3 border-top">
                        <div>
                            <span class="fx-3"><?= $company->user_name ?></span>
                        </div>
                        <div class="mt-2">
                            <span class="fx-n2 text-muted">이메일</span>
                            <span class="fx-n1 ml-2"><?= $company->user_email ?></span>
                        </div>
                        <div class="mt-2">
                            <span class="fx-n2 text-muted">누적 포인트</span>
                            <span class="fx-n1 ml-2"><?= $company->totalPoint ?>p</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <div class="mt-4 d-center">
        <a href="/companies?page=<?=$companies->prevPage?>" class="icon bg-red text-white" <?=$companies->prev ? "" : "disabled"?>>
            <i class="fa fa-angle-left"></i>
        </a>
        <?php for($i = $companies->start; $i <= $companies->end; $i++):?>
            <a href="/companies?page=<?=$i?>" class="icon <?= $i == $companies->page ? "bg-red text-white"  : "border text-muted" ?>"><?=$i?></a>
        <?php endfor;?>
        <a href="/companies?page=<?=$companies->nextPage?>" class="icon bg-red text-white" <?=$companies->next ? "" : "disabled"?>>
            <i class="fa fa-angle-right"></i>
        </a>
    </div>
</div>
<!-- /모든 업체 -->