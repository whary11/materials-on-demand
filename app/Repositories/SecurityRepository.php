<?php

namespace Laika\Infrastructure\Repositories;

use App\Contracts\SecurityRepositoryContract;
use App\Traits\Sp;
use Carbon\Carbon;
use DateTimeImmutable;
use Exception;
use Illuminate\Support\Facades\Redis;
use Laika\Infrastructure\Clients\UserClient;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Rsa\Sha256;
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
    public function createToken($user,string $apiKeyClient):?string{
        $now_time = new DateTimeImmutable("now");
        $issued_at = $now_time;
        $token_uuid = sha1(Carbon::now()->toDateTimeString().rand(1, 100000));
        $token_expiried_at = $now_time->modify("+50 year");
        $client_id = $this->getClient($apiKeyClient);

        if(is_null($client_id)){
            return null;
        }

        $t_identified_by = $this->saveToken($user['id'], $client_id, $token_uuid,  $token_expiried_at);

        if(is_null($t_identified_by)){
            return null;
        }

        $jwt = $this->generateToken($user,$client_id,$t_identified_by,$issued_at,$token_expiried_at);

        $this->saveTokenRedis($t_identified_by,$user);

        return $jwt;
    }

    /**
     *
     */
    public function generateToken($user, int $client_id, string $t_identified_by, DateTimeImmutable $issued_at, DateTimeImmutable $token_expiried_at):?string{
        $jwt = $this->config->builder()
                        // Configures the issuer (iss claim)
                        ->issuedBy('https://laika.com.co')
                        // Configures the audience (aud claim)
                        ->permittedFor($client_id)
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
    public function saveToken($user_id, $client_id, $token_uuid, $token_expiried_at):?string{
        $p_now = Carbon::now()->toDateTimeString();
        // (p_uuid text, p_token_expires varchar(100), p_token_refresh_exp varchar(100), p_user_id int)

        $result = (Array) $this->executeSp('CALL ksp_create_token(:token_uuid,:token_expiried_at,:token_refresh_expiried_at,:user_id)', [
            'token_uuid' => $token_uuid,
            'token_expiried_at' => $token_expiried_at->format('Y-m-d H:i:s'),
            'token_refresh_expiried_at' => null,
            'user_id' => $user_id,
        ]);

        if(!$result["status"]){
            return null;
        }else if(!isset($result["data"][0])){
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
            assert($token instanceof UnencryptedToken);
            //configurar validaciones
            $this->config->setValidationConstraints(
                new SignedWith($this->config->signer(), $this->config->verificationKey()),
                new ValidAt(SystemClock::fromUTC())
            );

            $constraints = $this->config->validationConstraints();
            try {
                $this->config->validator()->assert($token, ...$constraints);
            } catch (RequiredConstraintsViolated $e) {
                //dump($e);
                return [];
            }
        } catch (RuntimeException $ex) {
            //dump($ex);
            return [];
        }

        $token_uuid = $token->claims()->get("jti");
        $client_id = $token->claims()->get("aud");

        return ["token_uuid"=>$token_uuid,"client_id"=>$client_id[0]];
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
    public function removeTokenSql(string $token_uuid):void{
        $this->executeSp('CALL lsp_remove_token(:p_token_uuid)',['p_token_uuid'=>$token_uuid]);
    }

    /**
     *
     */
    public function validateTokenSql(string $token_uuid,int $client_id):Array{
        $result = (Array) $this->executeSp('CALL lsp_verify_token_ddd(:token_uuid,:client_id)', [
            'token_uuid' => $token_uuid,
            'client_id' => $client_id
        ]);

        if($result["status"]){
            return isset($result["data"][0])?(array) $result["data"][0]:[];
        }
        //dump("El token no existe en base de datos");
        return [];
    }

    /**
     *
     */
    public function getInfoUser(int $user_id):Array{
        return $this->userClient->getInfo($user_id);
    }
}
