<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\User;
use phpDocumentor\Reflection\Types\Array_;

class PasswordRecoverTest extends WebTestCase
{
    public function testGuestCanGenerateResetToken()
    {      
        $email = 'hadrien.giraudeau@gmail.com';
        $client = static::createClient();

        $crawler = $client->request('GET', '/password/recover');

        $client->submitForm('Envoyer', [
            'recovery_password[email]' => $email
        ]);

        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['email' => $email]);
        $this->assertNotNull($user->getPasswordToken());
    }

    public function testGuestCanChangePasswordWithValidToken()
    {
        $email = 'hadrien.giraudeau@gmail.com';
        $token = 'passwordToken';
        $newPassword = '1234567';

        $client = static::createClient();
       
        $client->request('GET', "/password/recover/{$email}/{$token}");

        $this->assertPageTitleSame('Récupération de mot de passe');

        $client->submitForm('Modifier le mot de passe', [
            'update_password[password][first]' => $newPassword,
            'update_password[password][second]' => $newPassword
        ]);

        $crawler = $client->followRedirect();

        $this->assertPageTitleSame('Login');
        /** Check le flash quand il sera implémenté */
    }

    public function testGuestCanThrowExceptionWithInvalidToken()
    {
        $email = 'hadrien.giraudeau@gmail.com';

        $client = static::createClient();
        
        $crawler = $client->request('GET', "/password/recover/{$email}/12345");

        $this->assertSame(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());

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

        $client = static::createClient();
       
        $client->request('GET', "/password/recover/{$email}/{$token}");

        $client->submitForm('Modifier le mot de passe', [
            'update_password[password][first]' => $password,
            'update_password[password][second]' => $passwordConfirm
        ]);
        
        $this->assertSelectorTextContains('li', $errorMsg);

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
