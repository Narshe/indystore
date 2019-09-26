<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\User;

class UserFixtures extends Fixture
{   
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;   
    }

    public function load(ObjectManager $manager)
    {
        $users = [
            'hadrien.giraudeau@gmail.com' => ['ROLE_ADMIN'],
            'baptiste.bonnand@gmail.com' => ['ROLE_ADMIN'],
            'michel.michel@gmail.com' => ['ROLE_USER']
        ];
        
        foreach($users as $email => $role) {

            $user = new User();
            $user->setEmail($email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, '123456'));
            $user->setRoles($role);
            
            if(getenv('APP_ENV') == 'test') {
                $user->setPasswordToken('passwordToken');
            }

            $manager->persist($user);
        }

        $manager->flush();
    }
}
