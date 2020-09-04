<!-- 비주얼 영역 -->
<div class="visual visual--sub">
    <div class="background background--black">
        <img src="/images/visual/sub.jpg" alt="배경 이미지" title="배경 이미지">
    </div>
    <div class="position-center text-center mt-4 w-100">
        <div class="fx-7 text-white font-weight-bold mb-3">로그인</div>
        <div class="fx-n1 text-gray">회원정보 > 로그인</div>
    </div>
</div>
<!-- /비주얼 영역 -->

<div class="container py-5">
    <form id="sign-in" method="post" enctype="multipart/form-data">
        <div class="pb-4 mb-4 border-bottom">
            <hr class="text-red">
            <div class="title text-red">로그인</div>
            <div class="text-title mt-4">로그인 후
                한지문화축제를 즐겨보세요</div>
        </div>
        <div class="form-group">
            <label>아이디</label>
            <input type="text" id="user_email" class="form-control" name="user_email" required>
            <div class="error text-red fx-n2 mt-1"></div>
        </div>
        <div class="form-group">
            <label>비밀번호</label>
            <input type="password" id="password" class="form-control" name="password" required>
            <div class="error text-red fx-n2 mt-1"></div>
        </div>
        <div class="form-group text-right">
            <button class="btn-custom">로그인</button>
        </div>
    </form>
</div>
