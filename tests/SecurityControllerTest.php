<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{   
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }
    
    public function testUserCanLoginWithCorrectCredentials()
    {
        $email = 'hadrien.giraudeau@gmail.com';
        $plainPassword = '123456';

        $this->client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $crawler = $this->client->submitForm('Se connecter', [
            'email' => $email,
            'password' => $plainPassword
        ]);
                
        $this->assertResponseStatusCodeSame(302);

        $crawler = $this->client->followRedirect();
        
        $this->assertPageTitleSame('Accueil');
        $this->assertSelectorTextContains('a', $email);

    }

    public function testUserCannotLoginWithIncorrectCredentials()
    {
        $email = 'hadrien.giraudeau@gmail.com';
        $plainPassword = '12345';

        $this->client->request('GET', '/login');

        $crawler = $this->client->submitForm('Se connecter', [
            'email' => $email,
            'password' => $plainPassword
        ]);
                
        $this->assertResponseStatusCodeSame(302);

        $crawler = $this->client->followRedirect();
        
        $this->assertPageTitleSame('Login');
        $this->assertSelectorTextContains('div.alert', 'Invalid credentials.');

    }

    public function testGuestMustCreateAccountBeforeLogin()
    {
        $email = "unknown@gmail.com";
        $plainPassword = '123456';

        $this->client->request('GET', '/login');

        $crawler = $this->client->submitForm('Se connecter', [
            'email' => $email,
            'password' => $plainPassword
        ]);
                
        $this->assertResponseStatusCodeSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        
        $this->assertPageTitleSame('Login');
        $this->assertSelectorTextContains('div.alert', 'Email could not be found');
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

}
