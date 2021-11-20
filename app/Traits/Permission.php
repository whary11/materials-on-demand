<?php

namespace App\Traits;

use Carbon\Carbon;

trait Permission
{
    use Sp;
    public function addPermissionsToRole(Int $role_id, Array $permissions_name):Array{
        try {
            $messages = [];
            foreach ($permissions_name as $key => $permission_name) {
                $resp = $this->executeSp("CALL ksp_add_permissions_to_role(:p_role_id, :p_permission_name,:p_now)", [
                    'p_role_id' => $role_id,
                    'p_permission_name' => $permission_name,
                    'p_now' => Carbon::now()->toDateTimeString()
                ]);
                if ($resp["status"]) {
                    $message = "$permission_name: ".$resp["data"][0]->msg;
                }else{
                    $message = "$permission_name: ".$resp["data"];
                }
                array_push($messages, $message);
            }
            return [
                'status' => true,
                'data' => $messages,
                'message' => "Todo bien."
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getmessage()
            ];
        }
    }

    public function addPermissionsToUser(Int $user_headquarter_id, Array $permissions_name):Array{
        try {
            $messages = [];
            foreach ($permissions_name as $key => $permission_name) {
                $resp = $this->executeSp("CALL ksp_add_permissions_to_user(:p_user_headquarter_id, :p_permission_name,:p_now)", [
                    'p_user_headquarter_id' => $user_headquarter_id,
                    'p_permission_name' => $permission_name,
                    'p_now' => Carbon::now()->toDateTimeString()
                ]);

                // dd($resp, (isset($resp["data"][0]->msg) ? $resp["data"][0]->msg : $resp["data"][0]->Message));
                if ($resp["status"]) {
                    $message = "$permission_name: ".(isset($resp["data"][0]->msg) ? $resp["data"][0]->msg : $resp["data"][0]->Message);
                }else{
                    $message = "$permission_name: ".$resp["data"];
                }
                array_push($messages, $message);
            }
            return [
                'status' => true,
                'data' => $messages,
                'message' => "Todo bien."
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getmessage()
            ];
        }
    }

    public function addRolesToUser(Int $user_headquarter_id, Array $roles_id):Array{
        try {
            $messages = [];
            foreach ($roles_id as $key => $role_id) {
                $resp = $this->executeSp("CALL ksp_add_role_to_user(:p_user_headquarter_id, :p_role_id,:p_now)", [
                    'p_user_headquarter_id' => $user_headquarter_id,
                    'p_role_id' => $role_id,
                    'p_now' => Carbon::now()->toDateTimeString()
                ]);
                if ($resp["status"]) {
                    $message = "$role_id: ".$resp["data"][0]->msg;
                }else{
                    $message = "$role_id: ".$resp["data"];
                }
                array_push($messages, $message);
            }

            return [
                'status' => true,
                'data' => $messages,
                'message' => "Todo bien."
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getmessage()
            ];
        }
    }
}
