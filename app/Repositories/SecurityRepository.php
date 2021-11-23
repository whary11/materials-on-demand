<?php

namespace App\Repositories;

use App\Contracts\SecurityRepositoryContract;
use App\Traits\Sp;
use Carbon\Carbon;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Laika\Infrastructure\Clients\UserClient;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Lcobucci\JWT\Validation\Constraint\ValidAt;
use RuntimeException;

class SecurityRepository implements SecurityRepositoryContract {
    use Sp;

    private $config;

    public function __construct()
    {
        $this->config = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::file(storage_path('private.key')),
            InMemory::file(storage_path('public.key'))
        );
    }

    /**
     *
     */
    public function createToken($user):?string{
        $now_time = new DateTimeImmutable("now");
        $issued_at = $now_time;
        $token_uuid = sha1(Carbon::now()->toDateTimeString().rand(1, 100000));
        $token_expiried_at = $now_time->modify("8 days");
        $t_identified_by = $this->saveToken($user['id'],$token_uuid,$token_expiried_at);
        if(is_null($t_identified_by)){
            return null;
        }

        $jwt = $this->generateToken($user,$t_identified_by,$issued_at,$token_expiried_at);

        // $this->saveTokenRedis($t_identified_by,$user);

        return $jwt;
    }

    /**
     *
     */
    public function generateToken($user, string $t_identified_by, DateTimeImmutable $issued_at, DateTimeImmutable $token_expiried_at):?string{
        $jwt = $this->config->builder()
                        // Configures the issuer (iss claim)
                        ->issuedBy('https://laika.com.co')
                        // Configures the audience (aud claim)
                        // ->permittedFor($client_id)
                        // Configures the id (jti claim)
                        ->identifiedBy($t_identified_by)
                        // Configures the time that the token was issue (iat claim)
                        ->issuedAt($issued_at)
                        // Configures the expiration time of the token (exp claim)
                        ->expiresAt($token_expiried_at)
                        // Configures a new claim, called "uid"
                        ->withClaim('uid', $user['id'])
                        ->relatedTo($user['id'])
                        ->withClaim('scopes', [])
                        ->withClaim('name', $user['name'])
                        // Builds a new token
                        ->getToken($this->config->signer(), $this->config->signingKey());

        return $jwt->toString();
    }

    /**
     *
     */
    public function getClient(string $tokenClient,string $uuid = null):?int{
        $resp = (Array) $this->executeSp('CALL lsp_get_client_id(:api_key,:uuid)', [
            "api_key" => $tokenClient,
            "uuid" => $uuid
        ]);

        if(!$resp["status"]){
            return null;
        }else if(!isset($resp["data"][0])){
            return null;
        }

        return $resp["data"][0]->id;
    }

    /**
     *
     */
    public function saveToken($user_id, $token_uuid, $token_expiried_at):?string{
        $p_now = Carbon::now()->toDateTimeString();
        // (p_uuid text, p_token_expires varchar(100), p_token_refresh_exp varchar(100), p_user_id int)

        $result = (Array) $this->executeSp('CALL ksp_create_token(:token_uuid,:token_expiried_at,:token_refresh_expiried_at,:user_id)', [
            'token_uuid' => $token_uuid,
            'token_expiried_at' => $token_expiried_at->format('Y-m-d H:i:s'),
            'token_refresh_expiried_at' => $token_expiried_at->modify("8 days")->format('Y-m-d H:i:s'),
            'user_id' => $user_id,
        ]);

        

        if(!$result["status"]){
            return null;
        }else if(!isset($result["data"][0])){
            return null;
        }else if(isset($result["data"][0]) && isset($result["data"][0]->Level)){
            return null;
        }

        return $token_uuid;
    }

    /**
     *
     */
    public function validateToken(string $jwt):?Array{
        try {
            $token = $this->config->parser()->parse($jwt);
            $now_time = new DateTimeImmutable("now");
            if ($token->isExpired($now_time)) {
                return [
                    'status' => false,
                    'message' => 'Token expirado',
                ];
            }
            assert($token instanceof UnencryptedToken);
            //configurar validaciones
            $this->config->setValidationConstraints(
                new SignedWith($this->config->signer(), $this->config->verificationKey()),
                new ValidAt(SystemClock::fromUTC())
            );

            $constraints = $this->config->validationConstraints();
            $token_uuid = $token->claims()->get("jti");
            $user_id = $token->claims()->get("uid");
            try {
                $this->config->validator()->assert($token, ...$constraints);

                // Buscar el token en base de datos
                $validate = $this->validateTokenSql($token);
                

                // dd($t[0]->count);

                if(!$validate){
                    return [
                        'status' => false,
                        'message' => "El token no existe en base de datos.",
                    ];
                }

            } catch (RequiredConstraintsViolated $e) {
                //dump($e);
                return [
                    'status' => false,
                    'message' => $e->getMessage(),
                ];
            }
        } catch (RuntimeException $ex) {
            //dump($ex);
            return [
                'status' => false,
                'message' => $ex->getMessage(),
            ];
        }

        

        return [
            'status' => true,
            'message' => 'Token válido',
            "token_uuid" => $token_uuid,
            "user_id" => $user_id
        ];
    }

    /**
     *
     */
    public function validateTokenRedis(string $token_uuid):Array{
        try{
            $response = json_decode(Redis::get($token_uuid));
            if(!is_null($response)){
                return (array) $response;
            }
        }catch(Exception $ex){
            //dump($ex);
        }
        return [];
    }

    /**
     *
     */
    public function saveTokenRedis(string $token_uuid,$data):void{
        try{
            $data = json_encode(serialize_data($data));
            Redis::set($token_uuid,$data);
        }catch(Exception $ex){
            //dump($ex);
        }
    }

    /**
     *
     */
    public function removeTokenRedis(string $token_uuid):void{
        try{
            Redis::del($token_uuid);
        }catch(Exception $ex){
            //dump($ex);
        }
    }

    /**
     *
     */
    public function removeTokenSql(Token $token):void{
        $this->executeSp('CALL lsp_remove_token(:p_token_uuid)',['p_token_uuid'=>$token_uuid]);
    }

    /**
     *
     */
    public function validateTokenSql(Token $token):Bool{
        $token_uuid = $token->claims()->get("jti");
        $user_id = $token->claims()->get("uid");
        $t = DB::select('SELECT COUNT(id) count FROM oauth_access_tokens WHERE uuid = ? AND user_id = ?', [$token_uuid, $user_id]);
        if ($t[0]->count == 0) {
            return false;
        }
        return true;
    }

    /**
     *
     */
    public function getInfoUser(int $user_id):Array{
        return $this->userClient->getInfo($user_id);
    }

    

}
