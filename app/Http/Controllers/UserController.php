<?php

namespace App\Http\Controllers;

use App\Repositories\SecurityRepository;
use App\Repositories\UserRepository;
use App\Traits\Sp;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userRepository;
    private $securityRepository;
    public function __construct(){
        $this->userRepository = new UserRepository;
        $this->securityRepository = new SecurityRepository;
    }
    public function login(Request $request){
        try {
            $email = $request->email;
            $password = $request->password;
            // Consultar el usuario por su email
            $user = $this->userRepository->getUserByEmail($email);
            $verify = $this->userRepository->passwordVerify($password,$user['password']);
            if ($verify) {
                // Crear token
                $token = $this->securityRepository->createToken($user);
                return $this->responseApi(true, ['type' => 'success', 'message' => 'Success'], [
                    'token' => $token,
                    'user' => $user,
                ]);
            }else {
                $this->responseApi(false, ['type' => 'error', 'message' => 'Credenciales incorrectas']);
            }
        } catch (\Throwable $th) {
            $this->responseApi(false, ['type' => 'error', 'message' => $th->getMessage()]);
        }
    }
}
