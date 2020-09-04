<?php
namespace Controller;

use App\DB;

class ApiController {
    function getUser($user_email){
        json_response(
            DB::who($user_email)
        );
    }
}