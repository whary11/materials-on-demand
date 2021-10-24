<?php

namespace App\Traits;

use Carbon\Carbon;

trait Headquarter
{
    use Sp;

    public function addHeadquartersToUser(Int $user_id, Array $headquarters):Array{
        try {
            $messages = [];
            foreach ($headquarters as $key => $headquarter) {
                $resp = $this->executeSp("CALL ksp_add_headquarter_to_user(:p_user_id, :p_headquarter_id,:p_now)", [
                    'p_user_id' => $user_id,
                    'p_headquarter_id' => $headquarter,
                    'p_now' => Carbon::now()->toDateTimeString()
                ]);
                if ($resp["status"]) {
                    $message = "$headquarter: ".$resp["data"][0]->msg;
                }else{
                    $message = "$headquarter: ".$resp["data"];
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


        return [];
    }
    
}
