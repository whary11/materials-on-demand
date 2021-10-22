<?php

namespace App\Http\Middleware;

use App\Repositories\SecurityRepository;
use App\Traits\ResponseApi;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;


class AuthMiddleware extends Middleware
{
    use ResponseApi;
    private $securityRepository;
    public function __construct(){
        $this->securityRepository = new SecurityRepository;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (!isset($request->header()['authorization'][0])) {
            return $this->responseApi(false, ['type' => 'unauthorized', 'content' => 'Unauthorized 1']);
        }
        $token = explode(" ", $request->header()['authorization'][0]);

        if(count($token) != 2){
            return $this->responseApi(false, ['type' => 'unauthorized', 'content' => 'Unauthorized 2']);
        }else if ($token[0] != "Bearer"){
            return $this->responseApi(false, ['type' => 'unauthorized', 'content' => 'Unauthorized 3']);
        }

        $token_string = $token[1];
        $token = $token_string;
        
        // Validar que la formación y fecha del token sea valida
        $token = $this->securityRepository->validateToken($token);
        if(!$token['status']){
            return $this->responseApi(false, ['type' => 'unauthorized', 'content' => $token['message']]);
        }
        // Autentica al usuario
        Auth::loginUsingId($token['user_id']);
        return $next($request);
    }
}
