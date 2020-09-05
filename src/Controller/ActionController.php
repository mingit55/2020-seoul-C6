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
        move_uploaded_file($image['tmp_name'], UPLOAD."/$filename");

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


    // 공지사항 삽입
    function insertNotice(){
        checkEmpty();
        extract($_POST);

        $files = $_FILES['files'];
        $filenames = [];
        $fileLength = count($files['name']);

        if($fileLength >= 1 && $files['name'][0] !== ""){
            for($i = 0; $i < $fileLength; $i++){
                $name = $files['name'][$i];
                $tmp_name = $files['tmp_name'][$i];
                $filename = time() . "-" . $name;
                $size = $files['size'][$i];

                if($name === "") continue;
                if($size > 1024 * 1024 * 10) back("파일은 10MB까지만 업로드 할 수 있습니다.");
                if($i > 3 ) back("파일은 4개까지만 업로드 할 수 있습니다.");
                
                move_uploaded_file($tmp_name, UPLOAD."/$filename");
                $filenames[] = $filename;
            }
        }

        DB::query("INSERT INTO notices(title, content, files) VALUES (?, ?, ?)", [$title, $content, json_encode($filenames)]);

        go("/notices", "공지를 작성했습니다.");
    }

    function updateNotice($id){
        $notice = DB::find("notices", $id);
        if(!$notice) back("대상을 찾을 수 없습니다.");

        checkEmpty();
        extract($_POST);

        $files = $_FILES['files'];
        $filenames = json_decode($notice->files);
        $fileLength = count($files['name']);

        if($fileLength >= 1 && $files['name'][0] !== ""){
            for($i = 0; $i < $fileLength; $i++){
                $name = $files['name'][$i];
                $tmp_name = $files['tmp_name'][$i];
                $filename = time() . "-" . $name;
                $size = $files['size'][$i];

                if($name === "") continue;
                if($size > 1024 * 1024 * 10) back("파일은 10MB까지만 업로드 할 수 있습니다.");
                if($i > 3 ) back("파일은 4개까지만 업로드 할 수 있습니다.");
                
                move_uploaded_file($tmp_name, UPLOAD."/$filename");
                $filenames[] = $filename;
            }
        }

        DB::query("UPDATE notices SET title = ?, content = ?, files = ? WHERE id = ?", [
            $title, $content, json_encode($filenames), $id
        ]);

        go("/notices/$id", "수정되었습니다.");
    }

    function deleteNotice($id){
        $notice = DB::find("notices", $id);
        if(!$notice) back("대상을 찾을 수 없습니다.");

        DB::query("DELETE FROM notices WHERE id = ?", [$id]);

        go("/notices", "삭제되었습니다.");
    }

    function downloadFile($filename){
        $filePath = UPLOAD."/$filename";
        if(!is_file($filePath)) back("대상을 찾을 수 없습니다.");

        $fileinfo = fileinfo($filename);
        header("Content-Disposition: attachement; filename={$fileinfo->name}");
        readfile($filePath);
    }

    // 문의 작성
    function insertInquire(){
        checkEmpty();
        extract($_POST);

        DB::query("INSERT INTO inquires(uid, title, content) VALUES (?, ?, ?)", [user()->id, $title, $content]);
        go("/inquires", "문의를 작성했습니다.");
    }

    // 답변 작성
    function insertAnswer(){
        checkEmpty();
        extract($_POST);
        $inquire = DB::find("inquires", $iid);
        if(!$inquire) back("대상을 찾을 수 없습니다.");

        DB::query("INSERT INTO answers(iid, content) VALUES (?, ?)", [$iid, $content]);
        go("/inquires", "답변을 작성했습니다.");
    }

    // 한지 추가
    function insertPaper(){
        checkEmpty();
        extract($_POST);

        $image = $_FILES['image'];
        $filename = time() . "-" . $image['name'];
        move_uploaded_file($image['tmp_name'], UPLOAD."/$filename");

        DB::query("INSERT INTO papers(uid, paper_name, width_size, height_size, point, hash_tags, image) VALUES (?, ?, ?, ?, ?, ?, ?)", [
            user()->id, $paper_name, $width_size, $height_size, $point, $hash_tags, "/uploads/". $filename
        ]);

        $pid = DB::lastInsertId();
        DB::query("INSERT INTO inventory(uid, pid, count) VALUES (?, ?, -1)", [user()->id, $pid]);
        
        go("/store", "한지를 추가했습니다.");
    }

    // 인벤토리 추가
    function insertInventory(){
        checkEmpty();
        extract($_POST);

        if($totalPoint > user()->point){
            back("포인트가 부족하여 구매하실 수 없습니다.");
            exit;
        }

        foreach(json_decode($cartList) as $item){
            $paper = DB::find("papers", $item->id);
            $point = $paper->point * $item->count;
            
            $exist = DB::fetch("SELECT * FROM inventory WHERE uid = ? AND pid = ?", [user()->id, $paper->id]);
            if($exist){
                DB::query("UPDATE inventory SET count = count + ? WHERE uid = ? AND pid = ?", [$item->count, user()->id, $paper->id]);
            } else {
                DB::query("INSERT INTO inventory(uid, pid, count) VALUES (?, ?, ?)", [user()->id, $paper->id, $item->count]);
            }

            DB::query("UPDATE users SET point = point - ? WHERE id = ?", [$point, user()->id]);
            DB::query("UPDATE users SET point = point + ? WHERE id = ?", [$point, $paper->uid]);
            DB::query("INSERT INTO history(uid, point) VALUES (?, ?)", [$paper->uid, $point]);
        }

        go("/store", "총 {$totalCount}개의 한지가 구매되었습니다.");
    }

    // 인벤토리 수정
    function updateInventory($id){
        $inventory = DB::find("inventory", $id);
        if(!$inventory || $inventory->uid !== user()->id) return;

        checkEmpty();
        extract($_POST);

        DB::query("UPDATE inventory SET count = ? WHERE id = ?", [$count, $id]);
    }

    // 인벤토리 삭제
    function deleteInventory($id){
        $inventory = DB::find("inventory", $id);
        if(!$inventory || $inventory->uid !== user()->id) return;

        DB::query("DELETE FROM inventory WHERE id = ?", [$id]);
    }

    // 작품 추가
    function insertArtwork(){
        checkEmpty();
        extract($_POST);

        $filename = upload_base64($image);

        DB::query("INSERT INTO artworks(uid, title, content, hash_tags, image) VALUES (?, ?, ?, ?, ?)", [
            user()->id, $title, $content, $hash_tags, $filename
        ]);

        go("/artworks", "작품을 출품했습니다.");
    }

    // 작품 수정
    function updateArtwork($id){
        $artwork = DB::find("artworks", $id);
        if(!$artwork || $artwork->uid !== user()->id) back("대상을 찾을 수 없습니다.");
        checkEmpty();
        extract($_POST);

        DB::query("UPDATE artworks SET title = ?, content = ?, hash_tags = ? WHERE id = ?", [$title, $content, $hash_tags, $id]);
        go("/artworks/$id", "작품을 수정했습니다.");
    }

    // 작품 삭제
    function deleteArtwork($id){
        $artwork = DB::find("artworks", $id);
        if(!$artwork || $artwork->uid !== user()->id) back("대상을 찾을 수 없습니다.");

        DB::query("DELETE FROM artworks WHERE id = ?", [$id]);
        go("/artworks", "작품을 삭제했습니다.");
    }

    // 작품 삭제 - 관리자
    function deleteArtworkByAdmin($id){
        $artwork = DB::find("artworks", $id);
        if(!$artwork) back("대상을 찾을 수 없습니다.");
        checkEmpty();
        extract($_POST);

        DB::query("UPDATE artworks SET rm_reason = ? WHERE id = ?", [$rm_reason, $id]);
        go("/artworks", "작품을 삭제했습니다.");
    }

    // 평점 등록
    function insertScore(){
        checkEmpty();
        extract($_POST);
        $artwork = DB::find("artworks", $aid);
        if(!$artwork) back("대상을 찾을 수 없습니다.");

        DB::query("INSERT INTO scores(uid, aid, score) VALUES (?, ?, ?)", [user()->id, $artwork->id, $score]);
        DB::query("UPDATE users SET score = score + ? WHERE id = ?", [100 * $score, $artwork->uid]);

        go("/artworks/$aid");
    }
}