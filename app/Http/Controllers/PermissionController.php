<?php

namespace App\Http\Controllers;

use App\Traits\Sp;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    use Sp;
    public function getPermissions(Request $request){
        $resp = $this->executeReadSp("CALL ksp_get_permissions(:p_search,:p_user_id,:p_type)", [
            'p_search' => $request->search,
            'p_user_id' => $request->user_id,
            'p_type' => 'permissions'
        ]);
        return $this->responseApi(true, ['type' => 'success', 'content' => 'Todo bien'], $resp['data']);
    }

    public function getRoles(Request $request){
        $resp = $this->executeReadSp("CALL ksp_get_permissions(:p_search,:p_user_id,:p_type)", [
            'p_search' => $request->search,
            'p_user_id' => $request->user_id,
            'p_type' => 'roles'
        ]);
        return $this->responseApi(true, ['type' => 'success', 'content' => 'Todo bien'], $resp['data']);
    }
}
