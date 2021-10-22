<?php

namespace App\Http\Controllers;

use App\Repositories\SecurityRepository;
use App\Repositories\UserRepository;
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
                unset($user["password"]);
                return $this->responseApi(true, ['type' => 'success', 'content' => 'Credenciales correctas'], [
                    'token' => $token,
                    'user' => $user,
                    'roles' => [
                        ["name" => "SUPER_ADMIN"]
                    ],
                    'permissions' => [],
                ]);
            }else {
                return $this->responseApi(false, ['type' => 'error', 'content' => 'Credenciales incorrectas']);
            }
        } catch (\Throwable $th) {
            $this->responseApi(false, ['type' => 'error', 'content' => $th->getMessage()]);
        }
    }
}
