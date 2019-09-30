<?php

namespace App\Tests\Auth;

use App\Tests\WebTestCase;
use App\Entity\User;

class RegistrationControllerTest extends WebTestCase
{
    public function testGuestCanRegisterANewAccount()
    {   
        $email = "testmail@gmail.com";
        $plainPassword = '123456';

        $client = $this->loginAs('guest');
        $crawler = $client->request('GET', '/register');
        
        $client->submitForm('S\'inscrire', [
            'registration_form[email]' => $email,
            'registration_form[plainPassword]' => $plainPassword
        ]);

        $crawler = $client->followRedirect();        
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['email' => $email]);

        $this->assertPageTitleSame('Accueil');
        
        $this->assertSame($user->getEmail(), $email);
    }

    /**
     * @dataProvider provideCredentials
     * @param string $email
     * @param string $password
     * @param string $errorMsg
     */
    public function testRegisterValidations(string $email, string $password, string $errorMsg)
    {

        $client = $this->loginAs('guest');
        $crawler = $client->request('GET', '/register');
        
        $client->submitForm('S\'inscrire', [
            'registration_form[email]' => $email,
            'registration_form[plainPassword]' => $password
        ]);

        $this->assertPageTitleSame('Register');
        $this->assertSelectorTextContains('form[name="registration_form"] li', $errorMsg);
    }

    /**
     * @return Array
     */
    public function provideCredentials(): Array
    {
        return [
            ['hadrien.giraudeau@gmail.com', '123456', 'Cet email est déjà utilisé'],
            ['test@test.com', '12', 'Votre mot de passe doit faire au moins 6 characters'],
            ['test', '123456', 'Veuillez entrer une adresse mail valide'],
            ['', '123456', 'Entrez votre email'],
            ['test@test.com', '', 'Entrez votre mot de passe']
            
        ];
    }
}
