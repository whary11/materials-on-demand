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

                // dd($resp);
                if ($resp["status"]) {
                    if (isset($resp["data"][0]->level)) {
                        # code...
                        $message = [
                            "status" => false,
                            "message" => "$headquarter: ".$resp["data"][0]->msg
                        ];
                    }else{
                        $message = [
                            "status" => true,
                            "message" => "$headquarter: ".$resp["data"][0]->msg
                        ];
                    }
                }else{

                    $message = [
                        "status" => false,
                        "message" => $message = "$headquarter: ".$resp["data"]
                    ];
                    
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
                'data' => [[
                    "status" => false,
                    "message" => $th->getmessage()
                ]],
                'message' => $th->getmessage()
            ];
        }


        return [];
    }
    
}
