<?php


namespace App\Service;

use Carbon\Carbon;
use Firebase\JWT\JWT;

class JWTEncoder
{
    // FIXME Is there a way to not hard code this?
    private const KEY = 'NkgfCreS;3gJxW2ZZfgp3';

    private const ALGORITHMS = ['HS256'];

    public function encode(array $payload): string
    {
        $payload['iat'] = Carbon::now()->getTimestamp();
        return JWT::encode($payload, self::KEY);
    }

    public function decode(string $token): array
    {
        return (array) JWT::decode($token, self::KEY, self::ALGORITHMS);
    }
}
