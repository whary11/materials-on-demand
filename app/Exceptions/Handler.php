<?php

namespace App\Exceptions;

use App\Traits\Sp;
use Carbon\Carbon;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Throwable;

class Handler extends ExceptionHandler
{
    use Sp;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    public function report(Throwable $e){
        try {
            $this->executeSp("CALL ksp_save_log_exception(:p_user_id,:p_code,:p_file,:p_line,:p_message,:p_now)", [
                "p_user_id" => Auth::id(),
                "p_code" => $e->getCode(),
                "p_file" => $e->getFile(),
                "p_line" => $e->getLine(),
                "p_message" => $e->getMessage(),
                "p_now" => Carbon::now()->toDateTimeString()
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
