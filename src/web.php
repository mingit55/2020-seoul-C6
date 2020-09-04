<?php
use App\Router;

Router::get("/", "ViewController@main");
Router::get("/init/admin", "ActionController@initialAdmin");

/**
 * 전주한지문화축제
 */
Router::get("/intro", "ViewController@intro");
Router::get("/roadmap", "ViewController@roadmap");

/**
 * 한지상품판매관
 */
Router::get("/companies", "ViewController@companies");
Router::get("/store", "ViewController@store", "login");

/**
 * 한지공예대전
 */
Router::get("/entry", "ViewController@entry", "login");
Router::get("/artworks", "ViewController@artworks");
Router::get("/artworks/{id}", "ViewController@artwork");


/**
 * 축제 공지사황
 */
Router::get("/notices", "ViewController@notices");
Router::get("/notices/{id}", "ViewController@notice");
Router::get("/inquires", "ViewController@inquires");

/**
 * 회원관리
 */
Router::get("/sign-in", "ViewController@signIn", "guest");
Router::get("/sign-up", "ViewController@signUp", "guest");

Router::post("/sign-in", "ActionController@signIn", "guest");
Router::post("/sign-up", "ActionController@signUp", "guest");
Router::get("/logout", "ActionController@logout");

Router::get("/api/users/{user_email}", "ApiController@getUser");


Router::start();