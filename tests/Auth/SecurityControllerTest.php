<?php

namespace App\Tests\Auth;

use App\Tests\WebTestCase;

class SecurityControllerTest extends WebTestCase
{   
    private $client;

    public function setUp()
    {
        $this->client = $this->loginAs('guest');
    }
    
    public function testUserCanLoginWithCorrectCredentials()
    {
        $email = 'hadrien.giraudeau@gmail.com';
        $plainPassword = '123456';

        $this->client->request('GET', '/login');

        $crawler = $this->client->submitForm('Se connecter', [
            'email' => $email,
            'password' => $plainPassword
        ]);
                
        $this->assertResponseStatusCodeSame(302);

        $crawler = $this->client->followRedirect();
        
        $this->assertPageTitleSame('Accueil');
        $this->assertSelectorTextContains('a', $email);

    }

    public function testUserCanRememberLogInfos() {

        $email = 'hadrien.giraudeau@gmail.com';
        $plainPassword = '123456';

        $this->client->request('GET', '/login');

        $crawler = $this->client->submitForm('Se connecter', [
            'email' => $email,
            'password' => $plainPassword,
            '_remember_me' => true
        ]);
        
        $cookieJar = $this->client->getCookieJar();
        $cookieName = $cookieJar->get('REMEMBERME')->getName();
        
        $this->assertSame('REMEMBERME', $cookieName);

    }

    /**
     * @dataProvider provideCredentials
     * @param string $email
     * @param string $plainPassword
     * @param string $errMsg
     */
    public function testUserCannotLoginWithIncorrectCredentials(string $email, string $plainPassword, string $errMsg)
    {

        $this->client->request('GET', '/login');

        $crawler = $this->client->submitForm('Se connecter', [
            'email' => $email,
            'password' => $plainPassword
        ]);
                
        $this->assertResponseStatusCodeSame(302);

        $crawler = $this->client->followRedirect();
        
        $this->assertPageTitleSame('Login');
        $this->assertSelectorTextContains('div.alert', $errMsg);

    }


    public function testAuthenticatedUserCannotAccessLoginForm()
    {
        $this->client->request('GET', '/login', [], [], [
            'PHP_AUTH_USER' => 'hadrien.giraudeau@gmail.com',
            'PHP_AUTH_PW'   => '123456',
        ]);

        $crawler = $this->client->followRedirect();

        $this->assertPageTitleSame('Accueil');
    }

    /**
     * @return Array
     */
    public function provideCredentials(): Array
    {
        return [
            ['hadrien.giraudeau@gmail.com', '12345', 'Invalid credentials.'],
            ['unknown@gmail.com', '123456', 'Cette adresse email n\'est associé à aucun compte'],
        ];
    }

}
