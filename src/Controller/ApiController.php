<?php
namespace Controller;

use App\DB;

class ApiController {
    function getUser($user_email){
        json_response(
            DB::who($user_email)
        );
    }

    function getPapers(){
        json_response(
            array_map(function($paper){
                $paper->hash_tags = json_decode($paper->hash_tags);
                return $paper;
            }, DB::fetchAll("SELECT P.*, U.user_name company_name
                        FROM papers P
                        LEFT JOIN users U ON U.id = P.uid
                        ORDER BY id DESC"))
        );  
    }

    function getInventory(){
        json_response(
            DB::fetchAll("SELECT I.*, paper_name, width_size, height_size, image
                            FROM inventory I
                            LEFT JOIN papers P ON P.id = I.pid
                            WHERE I.uid = ?", [user()->id])
        );
    }
}