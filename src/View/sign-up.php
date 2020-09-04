<!-- 비주얼 영역 -->
<div class="visual visual--sub">
    <div class="background background--black">
        <img src="/images/visual/sub.jpg" alt="배경 이미지" title="배경 이미지">
    </div>
    <div class="position-center text-center mt-4 w-100">
        <div class="fx-7 text-white font-weight-bold mb-3">회원가입</div>
        <div class="fx-n1 text-gray">회원정보 > 회원가입</div>
    </div>
</div>
<!-- /비주얼 영역 -->

<div class="container py-5">
    <form id="sign-up" method="post" enctype="multipart/form-data">
        <div class="pb-4 mb-4 border-bottom">
            <hr class="text-red">
            <div class="title text-red">회원가입</div>
            <div class="text-title mt-4">간단한 가입을 통해
                한지문화축제를 즐겨보세요</div>
        </div>
        <div class="form-group">
            <label>이메일</label>
            <input type="text" id="user_email" class="form-control" name="user_email" required>
            <div class="error text-red fx-n2 mt-1"></div>
        </div>
        <div class="form-group">
            <label>비밀번호</label>
            <input type="password" id="password" class="form-control" name="password" required>
            <div class="error text-red fx-n2 mt-1"></div>
        </div>
        <div class="form-group">
            <label>비밀번호 확인</label>
            <input type="password" id="passconf" class="form-control" name="passconf" required>
            <div class="error text-red fx-n2 mt-1"></div>
        </div>
        <div class="form-group">
            <label>이름</label>
            <input type="text" id="user_name" class="form-control" name="user_name" required>
            <div class="error text-red fx-n2 mt-1"></div>
        </div>
        <div class="form-group">
            <label>프로필 사진</label>
            <input type="file" id="image" class="form-control" name="image" required>
            <div class="error text-red fx-n2 mt-1"></div>
        </div>
        <div class="form-group">
            <label>회원 유형</label>
            <select name="type" id="type" class="form-control">
                <option value="normal">일반 회원</option>
                <option value="company">기업 회원</option>
            </select>
        </div>
        <div class="form-group text-right">
            <button class="btn-custom">회원가입</button>
        </div>
    </form>
</div>

<script src="/js/sign-up.js"></script>