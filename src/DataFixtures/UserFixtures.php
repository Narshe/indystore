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
        $hadri = new User();
        $boboldo = new User();
        $michel = new User();

        $hadri->setEmail('hadrien.giraudeau@gmail.com');
        $hadri->setPassword($this->passwordEncoder->encodePassword($hadri, '123456'));
        $hadri->setRoles(['ROLE_ADMIN']);

        $boboldo->setEmail('baptiste.bonnand@gmail.com');
        $boboldo->setPassword($this->passwordEncoder->encodePassword($boboldo, '123456'));
        $boboldo->setRoles(['ROLE_ADMIN']);

        $michel->setEmail('michel.michel@gmail.com');
        $michel->setPassword($this->passwordEncoder->encodePassword($michel, '123456'));

        $manager->persist($michel);
        $manager->persist($hadri);
        $manager->persist($boboldo);

        $manager->flush();
    }
}
