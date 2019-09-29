<?php

namespace App\Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase; 

class WebTestCase extends BaseWebTestCase
{

    public function loginAs(string $role, array $credentials = []) {

        $users = [
            'guest' => [],
            'user' => [
                'PHP_AUTH_USER' => 'michel.michel@gmail.com',
                'PHP_AUTH_PW' => '123456'
            ],
            'admin' => [
                'PHP_AUTH_USER' => 'hadrien.giraudeau@gmail.com',
                'PHP_AUTH_PW' => '123456'
            ]
        ];

        $cred = (!empty($credentials) || !isset($users[$role])) ? $credentials : $users[$role];

        return static::createClient([], $cred);
    }


}