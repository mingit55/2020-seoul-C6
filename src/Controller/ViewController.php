<?php
namespace Controller;

use App\DB;

class ViewController {
    function main(){
        view("main");
    }
    
    /**
     * 전주한지문화축제
     */
    function intro(){
        view("intro");
    }
    function roadmap(){
        view("roadmap");
    }

    /**
     * 한지상품판매관
     */
    function companies(){
        view("companies");
    }

    function store(){
        view("store");
    }

    /**
     * 한지공예대전
     */
    function entry(){
        view("entry");
    }
    
    function artworks(){
        view("artworks");
    }

    function artwork($id){
        $artwork = DB::find("artworks", $id);
        if(!$artwork) back("대상을 찾을 수 없습니다.");

        view("artwork");
    }

    /**
     * 축제 공지사항
     */
    function notices(){
        view("notices");
    }   
    function notice($id){
        $notice = DB::find("notices", $id);
        if(!$notice) back("대상을 찾을 수 없습니다.");
        
        view("notice");
    }

    function inquires(){
        if(admin()){
            view("inquires-admin");
        }
        else if(user()) {
            view("inquires-user");
        }
        else {
            go("/sign-in", "로그인 후 이용하실 수 있습니다.");
        }
    }


    /**
     * 회원 관리
     */
    function signIn(){
        view("sign-in");
    }

    function signUp(){
        view("sign-up");
    }
}