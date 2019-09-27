<?php

namespace App\Tests;

use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RouteTest extends WebTestCase
{   
    /**
     * @dataProvider unAuthenticatedRoutes
     * @param string $url
     * @param string $method
     */
    public function testGuestCanAccessUnauthenticatedRoutes(string $url, string $method)
    {
        $client = static::createClient();

        $client->request($method, $url);

        $this->assertResponseIsSuccessful();
    }

    /*
    public function testAuthenticatedUserCanAccessPublicsRoutes()
    {

    }
    */

    /**
     * @dataProvider adminRoutes
     * @param string $url
     * @param string $method
     */
    public function testAuthenticadAdminCanAccessAdminRoutes(string $url, string $method)
    {   
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'hadrien.giraudeau@gmail.com',
            'PHP_AUTH_PW' => '123456'
        ]);

        $client->request($method, $url);

        $this->assertResponseIsSuccessful();

    }

    /*
    public function testGuestCannotAccessAuthenticatedRoutes()
    {

    }
    */

    /**
     * @dataProvider adminRoutes
     * @param string $url
     * @param string $method
     */
    public function testAuthenticatedUserCannotAccessAdminRoutes(string $url, string $method)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'michel.michel@gmail.com',
            'PHP_AUTH_PW' => '123456'
        ]);

        $client->request($method, $url);

        $this->assertResponseStatusCodeSame(403);
    }
    

    /**
     * @return Array
     */
    public function unAuthenticatedRoutes(): Array
    {
        return [
            ['/', 'GET'],
            ['/register', 'GET'],
            ['/register', 'POST'],
            ['/login', 'GET'],
            ['/password/recover', 'GET'],
        ];
    }

    /**
     * @return Array
     */
    public function authenticatedRoutes(): Array
    {
        return [];
    }

    /**
     * @return Array
     */
    public function adminRoutes(): Array
    {
        return [
            ['/admin', 'GET']
        ];
    }

}
