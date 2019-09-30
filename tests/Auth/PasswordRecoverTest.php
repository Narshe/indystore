<?php

namespace App\Tests\Auth;

use App\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\User;

class PasswordRecoverTest extends WebTestCase
{   
    private $client;

    public function setUp()
    {
        $this->client = $this->loginAs('guest');
    }

    public function testGuestCanGenerateResetToken()
    {      
        $email = 'hadrien.giraudeau@gmail.com';


        $crawler = $this->client->request('GET', '/password/recover');

        $this->client->submitForm('Envoyer', [
            'recovery_password[email]' => $email
        ]);

        $user = $this->client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['email' => $email]);
        $this->assertNotNull($user->getPasswordToken());
    }

    public function testGuestCanChangePasswordWithValidToken()
    {
        $email = 'hadrien.giraudeau@gmail.com';
        $token = 'passwordToken';
        $newPassword = '1234567';

        $this->client = $this->loginAs('guest');
       
        $this->client->request('GET', "/password/recover/{$email}/{$token}");

        $this->assertPageTitleSame('Récupération de mot de passe');

        $this->client->submitForm('Modifier le mot de passe', [
            'update_password[password][first]' => $newPassword,
            'update_password[password][second]' => $newPassword
        ]);

        $crawler = $this->client->followRedirect();

        $this->assertPageTitleSame('Login');
        /** Check le flash quand il sera implémenté */
    }

    public function testGuestCanThrowExceptionWithInvalidToken()
    {
        $email = 'hadrien.giraudeau@gmail.com';
        
        $crawler = $this->client->request('GET', "/password/recover/{$email}/12345");

        $this->assertSame(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

    }

    /**
     * @dataProvider provideValidation
     * @param string $password
     * @param string $passwordConfirm
     * @param string $errorMsg
     */
    public function testUpdatePasswordFormValidation(string $password, string $passwordConfirm, string $errorMsg)
    {
        $email = 'hadrien.giraudeau@gmail.com';
        $token = 'passwordToken';
               
        $this->client->request('GET', "/password/recover/{$email}/{$token}");

        $this->client->submitForm('Modifier le mot de passe', [
            'update_password[password][first]' => $password,
            'update_password[password][second]' => $passwordConfirm
        ]);
        
        $this->assertSelectorTextContains('form[name="update_password"] li', $errorMsg);

    }

    /**
     * @return Array
     */
    public function provideValidation(): Array
    {
        return [
            ['123456', '1234567', 'Les mot de passe doivent être identiquent'],
            ['123', '123', 'Votre mot de passe doit faire au moins 6 characters'],
            ['', '', 'Entrez votre mot de passe']
        ];
    }
}
