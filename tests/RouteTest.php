<?php

namespace App\Tests;

use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RouteTest extends WebTestCase
{   
    /**
     * @dataProvider unAuthenticatedRoutes
     * @param string $url
     * @param array $method
     */
    public function testGuestCanAccessUnauthenticatedRoutes(string $url, array $method)
    {
        $client = static::createClient();

        foreach($method as $m) {
            $client->request($m, $url);
            $this->assertResponseIsSuccessful();
        }

    }

    /*
    public function testAuthenticatedUserCanAccessPublicsRoutes()
    {

    }
    */

    /**
     * @dataProvider adminRoutes
     * @param string $url
     * @param array $method
     */
    public function testAuthenticadAdminCanAccessAdminRoutes(string $url, array $method)
    {   
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'hadrien.giraudeau@gmail.com',
            'PHP_AUTH_PW' => '123456'
        ]);
            
        foreach($method as $m) {
            $client->request($m, $url);
            $this->assertResponseIsSuccessful();
        }
    }

    /*
    public function testGuestCannotAccessAuthenticatedRoutes()
    {

    }
    */

    /**
     * @dataProvider adminRoutes
     * @param string $url
     * @param array $method
     */
    public function testAuthenticatedUserCannotAccessAdminRoutes(string $url, array $method)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'michel.michel@gmail.com',
            'PHP_AUTH_PW' => '123456'
        ]);
        
        foreach($method as $m) {
            $client->request($m, $url);
            $this->assertResponseStatusCodeSame(403);
        }

    }
    

    /**
     * @return Array
     */
    public function unAuthenticatedRoutes(): Array
    {
        return [
            ['/', ['GET']],
            ['/register', ['GET', 'POST']],
            ['/login', ['GET']],
            ['/password/recover', ['GET', 'POST']],
            ['/password/recover/hadrien.giraudeau@gmail.com/passwordToken', ['GET', 'PUT']],
            ['/games', ['GET']],
            ['/games/10', ['GET']]
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
            ['/admin', ['GET']],
            ['/admin/games', ['GET']],
            ['/admin/games/edit/10', ['GET', 'PUT']],
         //   ['/admin/games/10', ['DELETE']]
        ];
    }

}
