<?php
use App\DB;

function dump(){
    foreach(func_get_args() as $arg){
        echo "<pre>";
        var_dump($arg);
        echo "</pre>";
    }
}

function dd(){
    foreach(func_get_args() as $arg){
        echo "<pre>";
        var_dump($arg);
        echo "</pre>";
    }
    exit;
}

function user(){
    if(isset($_SESSION['user'])){
        $user = DB::who($_SESSION['user']->user_email);
        if(!$user) {
            unset($_SESSION['user']);
            go("/", "로그인 정보를 찾을 수 없습니다. 로그아웃 됩니다.");
        } else {
            return $user;
        }
    } else return false;
}

function company(){
    return user() && user()->type == 'company' ? user() : false;
}

function admin(){
    return user() && user()->type == 'admin' ? user() : false;
}

function view($viewName, $data = []){
    extract($data);

    require VIEW."/header.php";
    require VIEW."/$viewName.php";
    require VIEW."/footer.php";
    exit;
}

function extname($filename){
    return substr($filename, strrpos($filename, "."));
}

function checkEmpty(){
    foreach($_POST as $input){
        if(trim($input) === "") back("모든 정보를 입력해 주세요");
    }
}

function go($url, $message = ""){
    echo "<script>";
    if($message !== "") echo "alert('$message');";
    echo "location.href='$url';";
    echo "</script>";
    exit;
}

function back($message = ""){
    echo "<script>";
    if($message !== "") echo "alert('$message');";
    echo "history.back();";
    echo "</script>";
    exit;
}

function json_response($data){
    header("Content-Type: application/json");
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function fileinfo($filename){
    $name = substr($filename, strpos($filename, "-") + 1);
    $localPath = UPLOAD."/$filename";
    $viewPath = "/uploads/$filename";
    $fileSize = number_format(filesize($localPath) / 1024 , 1) . "KB";
    $extname = extname($filename);
    return (object)compact("localPath", "viewPath", "fileSize", "extname");
}

function upload_base64($data){
    $temp = explode(";base64,", $data);
    $data = base64_decode($temp[1]);
    $filename = time() . ".jpg";
    file_put_contents(UPLOAD."/$filename", $data);
    return $filename;
}

function dt($time){
    return date("Y년 m월 d일", strtotime($time));
}

function enc($output){
    return nl2br( str_replace(" ", "&nbsp;", htmlentities($output))  );
}