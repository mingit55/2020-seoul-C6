<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>전주한지문화축제</title>
    <script src="/js/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <script src="/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/common.js"></script>
</head>
<body>
    <input type="checkbox" id="open-aside" hidden>
    <!-- 헤더 영역 -->
    <header>
        <div class="container d-between h-100">
            <div class="align-center">
                <a href="/" class="logo">
                    <img src="/images/logo.svg" alt="전주한지문화축제" title="전주한지문화축제" height="50">
                </a>
                <div class="nav ml-5 d-none d-lg-flex">
                    <div class="nav__item">
                        <a href="/intro">전주한지문화축제</a>
                        <div class="nav__list">
                            <a href="/intro">개요/연혁</a>
                            <a href="/roadmap">찾아오시는길</a>
                        </div>
                    </div>
                    <div class="nav__item">
                        <a href="/companies">한지상품판매관</a>
                        <div class="nav__list">
                            <a href="/companies">한지업체</a>
                            <a href="/store">온라인스토어</a>
                        </div>
                    </div>
                    <div class="nav__item">
                        <a href="/entry">한지공예대전</a>
                        <div class="nav__list">
                            <a href="/entry">출품신청</a>
                            <a href="/artworks">참가작품</a>
                        </div>
                    </div>
                    <div class="nav__item">
                        <a href="/notices">축제공지사항</a>
                        <div class="nav__list">
                            <a href="/notices">알려드립니다</a>
                            <a href="/inquires">1:1문의</a>
                        </div>
                    </div>
                    <div class="nav__background"></div>
                </div>
            </div>
            <div class="auth d-none d-lg-block">
                <?php if(user()):?>
                    <a href="#"><?=user()->user_name?>(<?=user()->point?>p)</a>
                    <a href="/logout">로그아웃</a>
                <?php else:?>
                    <a href="/sign-in">로그인</a>
                    <a href="/sign-up">회원가입</a>
                <?php endif;?>
            </div>
            <label for="open-aside" class="icon-bars d-lg-none">
                <span></span>
                <span></span>
                <span></span>
            </label>
        </div>
    </header>
    <!-- /헤더 영역 -->

    <!-- 사이드바 -->
    <aside class="d-lg-none">
        <label for="open-aside" class="aside__background"></label>
        <div class="aside__content">
            <div class="auth auth--aside">
            <?php if(user()):?>
                    <a href="#"><?=user()->user_name?>(<?=user()->point?>p)</a>
                    <a href="/logout">로그아웃</a>
                <?php else:?>
                    <a href="/sign-in">로그인</a>
                    <a href="/sign-up">회원가입</a>
                <?php endif;?>
            </div>
            <div class="nav nav--aside">
                <div class="nav__item">
                    <a href="/intro">전주한지문화축제</a>
                    <div class="nav__list">
                        <a href="/intro">개요/연혁</a>
                        <a href="/roadmap">찾아오시는길</a>
                    </div>
                </div>
                <div class="nav__item">
                    <a href="/companies">한지상품판매관</a>
                    <div class="nav__list">
                        <a href="/companies">한지업체</a>
                        <a href="/store">온라인스토어</a>
                    </div>
                </div>
                <div class="nav__item">
                    <a href="/entry">한지공예대전</a>
                    <div class="nav__list">
                        <a href="/entry">출품신청</a>
                        <a href="/artworks">참가작품</a>
                    </div>
                </div>
                <div class="nav__item">
                    <a href="/notices">축제공지사항</a>
                    <div class="nav__list">
                        <a href="/notices">알려드립니다</a>
                        <a href="/inquires">1:1문의</a>
                    </div>
                </div>
            </div>
        </div>
    </aside>
    <!-- /사이드바 -->
