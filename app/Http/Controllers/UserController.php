<?php

namespace App\Http\Controllers;

use App\Repositories\SecurityRepository;
use App\Repositories\UserRepository;
use App\Traits\Headquarter;
use App\Traits\Permission;
use App\Traits\Sp;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use Headquarter, Permission;
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
            return $this->responseApi(false, ['type' => 'error', 'content' => $th->getMessage()]);
        }
    }

    public function getUsersManage(Request $request){
        $limit = isset($request->limit) ? $request->limit: 15;
        $offset = isset($request->page)?(($request->page - 1) * $limit):null;

        $users = $this->executeReadSp("CALL ksp_get_users_manage(:p_offset, :p_limit)",[
            'p_offset' => $offset,
            'p_limit' => $limit,
        ]);
        $newUsers = [];
        foreach ($users['data'] as $key => $user) {
            $user = (Array) $user;
            $headquarters = explode('&&&&&', $user['headquarters_name']);
            $newHeadquarters = [];


            
            foreach ($headquarters as $key => $headquarter) {
                $headquarter = explode('|||||', $headquarter);

                // dd($headquarter[2]);
                $finlRoles = [];
                // Consultar roles por bodega.
                $roles = $this->executeReadSp("CALL ksp_get_roles_to_user(:p_user_headquarter_id)",[
                    'p_user_headquarter_id' => $headquarter[2],
                ]);

                $roles = array_map(function ($role){
                    return (Array) $role;
                },$roles['data']);

                $filter = collect($roles)->groupBy("type")->values()->toArray();
                // $roles = collect($filter[0])->groupBy("role_name")->values()->toArray();
                $especial_permissions =  isset($filter[1]) ? $filter[1] : [];

                
                $newRoles = collect($filter[0])->groupBy("role_name")->values()->toArray();
                $r = [];
                foreach ($newRoles as $key => $newRole) {
                    $permissions = [];
                    foreach ($newRole as $key => $rr) {
                        if (isset($rr['permission_id'])) {
                            array_push($permissions, [
                                'id' => $rr['permission_id'],
                                'name' => $rr['permission_name']
                            ]);
                        }
                    }

                    // dd($newRole);
                    $newRole = [
                        'name' => $newRole[0]['role_name'],
                        'permissions' => $permissions
                    ];
                    array_push($finlRoles, $newRole);
                }
                // Consultar permisos especiales.
                

                $headquarter = [
                    'id' => $headquarter[0],
                    'name' => $headquarter[1],
                    'user_headquarter_id' => (Int) $headquarter[2],
                    'permissions' => $especial_permissions,
                    'roles' => $finlRoles,
                ];
                array_push($newHeadquarters,$headquarter);
            }
            $user['headquarters'] = $newHeadquarters;
            array_push($newUsers,$user);
        }
        return ['data' => $newUsers, 'count' => count($newUsers) == 0 ? 0 : $newUsers[0]['count']];
        
    }

    public function getHeadquartersNotUser(Request $request):Array{
        $resp = $this->executeReadSp("CALL ksp_get_headquarters_not_user(:p_user_id,:p_search)", [
            'p_user_id' => $request->user_id,
            'p_search' => $request->search
        ]);
        return $this->responseApi(true, ['type' => 'success', 'content' => 'Todo bien'], $resp['data']);
    }

    public function addHeadquarters(Request $request):Array{
        $resp = $this->addHeadquartersToUser($request->user_id, $request->headquarters);
        return $this->responseApi($resp["status"], ['type' => $resp["status"] ? 'success' : 'error', 'content' => $resp["message"]], $resp);
    }


    public function addPermissions(Request $request):Array{
        $resp = $this->addPermissionsToUser($request->user_headquarter_id, $request->permissions);
        return $this->responseApi($resp["status"], ['type' => $resp["status"] ? 'success' : 'error', 'content' => $resp["message"]], $resp);
    }
}
