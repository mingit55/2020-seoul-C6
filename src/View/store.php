<input type="hidden" id="uid" value="<?=user()->id?>">

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


<!-- 검색 영역 -->
<div class="container py-5">
    <div class="p-3 border bg-light d-center">
        <div id="search-module" class="w-100 mr-4" data-name="search">
            
        </div>
        <button class="btn-search icon text-red"><i class="fa fa-search"></i></button>
    </div>
</div>
<!-- /검색 영역 -->

<!-- 상품 목록 -->
<div class="container py-5">
    <div class="d-between mb-4 pb-3 border-bottom">
        <div>
            <hr class="bg-red">
            <div class="title text-red">상품 목록</div>
        </div>
        <button class="btn-custom" data-target="#add-form" data-toggle="modal">상품 등록</button>
    </div>
    <div id="store" class="row">
    </div>
</div>
<!-- /상품 목록 -->

<!-- 장바구니 -->
<div class="container py-5">
    <div class="mb-4">
        <hr class="bg-yellow">
        <div class="title text-yellow">장바구니</div>
    </div>
    <div class="t-head">
        <div class="cell-50">상품 정보</div>
        <div class="cell-20">구매 수량</div>
        <div class="cell-20">합계 포인트</div>
        <div class="cell-10">-</div>
    </div>
    <div id="cart"></div>
    <div class="mt-4 d-between">
        <div>
            <span class="fx-n1">총 합계 포인트</span>
            <span class="total-point ml-3 fx-3 text-red">0</span>
            <span class="fx-n2 text-muted">p</span>
        </div>
        <div>
            <span class="fx-n1">보유 포인트</span>
            <span class="ml-3 fx-3 text-red"><?=user()->point?></span>
            <span class="fx-n2 text-muted">p</span>
        </div>  
        <form id="buy-form" action="/insert/inventory" method="post">
            <input type="hidden" id="totalPoint" name="totalPoint">
            <input type="hidden" id="totalCount" name="totalCount">
            <input type="hidden" id="cartList" name="cartList">
            <button class="btn-bordered">구매 완료</button>
        </form>
    </div>
</div>
<!-- /장바구니 -->

<!-- 상품 등록 -->
<form action="/insert/papers" id="add-form" class="modal fade" method="post" enctype="multipart/form-data">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="fx-4">상품 등록</div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" id="base64">
                    <label>이미지</label>
                    <input type="file" id="image" name="image" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>상품명</label>
                    <input type="text" name="paper_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>업체명</label>
                    <input type="text" name="company_name" class="form-control" value="<?= user()->user_name ?>" readonly required>
                </div>
                <div class="form-group">
                    <label>가로 사이즈</label>
                    <input type="number" name="width_size" class="form-control" min="100" max="1000" required>
                </div>
                <div class="form-group">
                    <label>세로 사이즈</label>
                    <input type="number" name="height_size" class="form-control" min="100" max="1000" required>
                </div>
                <div class="form-group">
                    <label>포인트</label>
                    <input type="number" name="point" class="form-control" min="10" max="1000" step="10" required>
                </div>
                <div class="form-group">
                    <label>해시 태그</label>
                    <div id="entry-module" data-name="hash_tags"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-filled">추가 완료</button>
            </div>
        </div>
    </div>
</form>
<!-- /상품 등록 -->

<script src="/js/store.js"></script>