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

Router::post("/insert/papers", "ActionController@insertPaper", "company");
Router::post("/insert/inventory", "ActionController@insertInventory", "user");

Router::get("/api/papers", "ApiController@getPapers", "login");

/**
 * 한지공예대전
 */
Router::get("/entry", "ViewController@entry", "login");
Router::get("/artworks", "ViewController@artworks");
Router::get("/artworks/{id}", "ViewController@artwork");

Router::post("/update/inventory/{id}", "ActionController@updateInventory");
Router::post("/delete/inventory/{id}", "ActionController@deleteInventory");
Router::post("/insert/artworks", "ActionController@insertArtwork", "user");
Router::post("/update/artworks/{id}", "ActionController@updateArtwork", "user");
Router::get("/delete/artworks/{id}", "ActionController@deleteArtwork", "user");
Router::post("/delete-admin/artworks/{id}", "ActionController@deleteArtworkByAdmin", "admin");

Router::post("/insert/scores", "ActionController@insertScore", "user");

Router::get("/api/inventory", "ApiController@getInventory");
/**
 * 축제 공지사황
 */
Router::get("/notices", "ViewController@notices", "login");
Router::get("/notices/{id}", "ViewController@notice", "login");
Router::get("/inquires", "ViewController@inquires", "login");

Router::post("/insert/notices", "ActionController@insertNotice", "admin");
Router::post("/update/notices/{id}", "ActionController@updateNotice", "admin");
Router::get("/delete/notices/{id}", "ActionController@deleteNotice", "admin");
Router::get("/download/{filename}", "ActionController@downloadFile", "login");
Router::post("/insert/inquires", "ActionController@insertInquire", "user");
Router::post("/insert/answers", "ActionController@insertAnswer", "admin");

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