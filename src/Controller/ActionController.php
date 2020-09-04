<?php
namespace Controller;

use App\DB;

class ActionController {
    function initialAdmin(){
        $exist = DB::who("admin");

        if(!$exist){
            DB::query("INSERT INTO users(user_email, password, user_name, type) VALUES (?, ?, ?, ?)",[
                "admin", "1234", "관리자", "admin"
            ]);
        }
    }


    // 로그인
    function signIn(){
        checkEmpty();
        extract($_POST);

        $user = DB::who($user_email);
        if(!$user) back("아이디와 일치하는 회원이 존재하지 않습니다.");
        else if($user->password !== $password) back("비밀번호가 일치하지 않습니다.");

        $_SESSION['user'] = $user;

        go("/", "로그인 되었습니다.");
    }

    // 회원가입
    function signUp(){
        checkEmpty();
        extract($_POST);

        $image = $_FILES['image'];
        $filename = time() . "-" . $image['name'];
        move_uploaded_file($image['tmp_name'], $filename);

        DB::query("INSERT INTO users(user_email, password, user_name, image, type) VALUES (?, ?, ?, ?, ?)", [
            $user_email, $password, $user_name, $filename, $type
        ]);

        go("/", "회원가입 되었습니다.");
    }

    // 로그아웃
    function logout(){
        unset($_SESSION['user']);
        go("/", "로그아웃 되었습니다.");
    }
}