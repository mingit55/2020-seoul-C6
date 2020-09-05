<?php
namespace App;

class Router {
    static $pages = [];
    static function __callStatic($name, $args){
        if($name === strtolower($_SERVER['REQUEST_METHOD'])){
            self::$pages[] = $args;
        }
    }

    static function start() {
        $currentURL = explode("?", $_SERVER['REQUEST_URI'])[0];
        // dd($currentURL, self::$pages);
        
        foreach(self::$pages as $page){
            $url = $page[0];
            $action = explode("@", $page[1]);
            $permission = isset($page[2]) ? $page[2] : null;

            $regex = preg_replace("/({[^\/]+})/", "([^/]+)", $url);
            $regex = preg_replace("/\//", "\\/", $regex);
            if(preg_match("/^{$regex}$/", $currentURL, $matches)){
                if($permission){
                    if($permission === "guest" && user()) go("/", "로그인한 회원은 접근할 수 없습니다.");
                    if($permission === "login" && !user()) go("/sign-in", "로그인 후 이용하실 수 있습니다.");
                    if($permission === "user" && (!user() || admin())) go("/", "회원만 이용할 수 있습니다.");
                    if($permission === "company" && !company()) back("기업 회원만 이용가능합니다.");
                    if($permission === "admin" && !admin()) back("권한이 없습니다.");
                }
                unset($matches[0]);
                $conName = "Controller\\{$action[0]}";
                $con = new $conName();
                $con->{$action[1]}(...$matches);
                exit;
            }
        }
        http_response_code(404);
    }
}