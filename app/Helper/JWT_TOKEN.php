<?php

namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWT_TOKEN
{

   public static function createToken ($userEmailId):string {

        $KEY = env('JWT_KEY');

        $payload = [
            'iss' => 'Laravel-Token',
            'iat' => time(),
            'exp' => time()+60*60,
            'userEmail' => $userEmailId
        ];

      return  JWT::encode($payload, $KEY, 'HS256');

    }

    public static function createTokenForSetPassword ($userEmailId):string {

        $KEY = env('JWT_KEY');

        $payload = [
            'iss' => 'Laravel-Token',
            'iat' => time(),
            'exp' => time()+60*5,
            'userEmail' => $userEmailId
        ];

        return  JWT::encode($payload, $KEY, 'HS256');

    }


   public static function verifyToken ($token):string {

        try {

            $KEY = env('JWT_KEY');

            $decode = JWT::decode($token,new Key($KEY,'HS256'));

            return $decode->userEmail;

        }catch (Exception $exception){
            return 'unauthorized';
        }



    }

}
