<?php
namespace App\Contracts;

use DateTimeImmutable;
use Lcobucci\JWT\Token;

interface SecurityRepositoryContract {
    public function createToken($user):?string;
    public function getClient(string $api_key_client,string $uuid = null):?Int;
    public function saveToken(int $user_id, string $token_uuid, DateTimeImmutable $token_expiried_at):?string;
    public function generateToken($user, string $t_identified_by, DateTimeImmutable $issued_at, DateTimeImmutable $expires_at_token):?string;
    public function removeTokenRedis(string $token_uuid):void;
    public function removeTokenSql(Token $token):void;
    public function saveTokenRedis(string $token_uuid,$data):void;
    public function validateTokenRedis(string $token_uuid):Array;
    public function validateToken(string $jwt):?Array;
    public function validateTokenSql(Token $token):Bool;
    public function getInfoUser(int $user_id):Array;
}
