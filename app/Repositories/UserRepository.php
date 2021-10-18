<?php 
namespace App\Repositories;

use App\Traits\Sp;
use Exception;

class UserRepository {
    use Sp;
    public function getUserByEmail($email){
        try {
            $resp = $this->executeReadSp("CALL ksp_get_user_by_email(:p_email)",[
                'p_email' => $email
            ]);
            if (!$resp['status']) {
                throw new Exception(json_encode($resp));
            }
            return (Array) $resp["data"][0];
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }

    public function passwordVerify(String $password, String $hash):Bool{
        return password_verify($password,$hash);
    }
}