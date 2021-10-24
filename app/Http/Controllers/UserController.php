<?php

namespace App\Http\Controllers;

use App\Repositories\SecurityRepository;
use App\Repositories\UserRepository;
use App\Traits\Sp;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use Sp;
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

    public function getUsersManage(Request $request){
        $offset = 0;
        $users = $this->executeReadSp("CALL ksp_get_users_manage(:p_offset, :p_limit)",[
            'p_offset' => $offset,
            'p_limit' => $request->limit,
        ]);
        $newUsers = [];
        foreach ($users['data'] as $key => $user) {
            $user = (Array) $user;
            $headquarters = explode('&&&&&', $user['headquarters_name']);
            $newHeadquarters = [];
            foreach ($headquarters as $key => $headquarter) {
                $headquarter = explode('|||||', $headquarter);
                // Consultar roles por bodega.

                // Consultar permisos especiales.
                $headquarter = [
                    'id' => $headquarter[0],
                    'name' => $headquarter[1],
                    'permissions' => [],
                    'roles' => [],
                ];
                array_push($newHeadquarters,$headquarter);
            }
            $user['headquarters'] = $newHeadquarters;
            array_push($newUsers,$user);
        }



        return ['data' => $newUsers, 'count' => count($newUsers)];
        return [
            'data' => [
                [
                    'id' => 1,
                    'fullname' => 'Luis Fernando Raga Renteria',
                    'age' => 31,
                    'email' => 'whary11@gmail.com',
                    'avatar'=>'https://scontent.fbog11-1.fna.fbcdn.net/v/t1.6435-1/p160x160/67654490_10218672031367891_7179299875513696256_n.jpg?_nc_cat=102&ccb=1-5&_nc_sid=dbb9e7&_nc_ohc=BPI0w_1xZLEAX8ZTXc0&_nc_ht=scontent.fbog11-1.fna&oh=67c557923a9dc4c94f4606c0e4639201&oe=619B2085',
                    'headquarters' => [
                        [
                            'id' => 1,
                        'name' => 'Sur de bogotá',
                        'permissions' => ['crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios'],
                        'roles' => [
                            ['name' => 'ADMIN', 'permissions' => ['crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios']],
                            ['name' => 'TEST', 'permissions' => ['crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios','crear usuarios', 'ver usuarios']],
                            ['name' => 'SUPER_ADMIN', 'permissions' => []],
                        ]
                        ]
                    ]
                ],
                
            ],
            'count' => 100
        ];
    }
}
