<?php

namespace App\Traits;
use Illuminate\Support\Facades\DB;

trait ResponseApi
{

    public function responseApi($statusTransaction, $message = [], $data = []): array
    {
        if (strtolower($message['type']) == 'success') {
            $message = $message;
            $code = 200;
        } else if (strtolower($message['type']) == 'error') {
            $message = $message;
            $code = 500;
        } else if (strtolower($message['type']) == 'warning') {
            $message = $message;
            $code = 300;
        } else if (strtolower($message['type']) == 'unauthorized') {
            $message = $message;
            $code = 401;
        } else {
            $message = $message;
            $code = 500;
        }
        return [
            'code' => $code,
            'transaction' => [
                'status' => $statusTransaction
            ],
            'data' => $data,
            'message' => $message
        ];
    }
}
