<?php
namespace App\DataFixtures\Processor;

use Fidry\AliceDataFixtures\ProcessorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

final class UserProcessor implements ProcessorInterface
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @inheritdoc
     */
    public function preProcess(string $fixtureId, $object): void
    {
        if (false === $object instanceof User) {
            return;
        }

        $object->setPassword($this->passwordEncoder->encodePassword($object, $object->getPassword()));
    }

    /**
     * @inheritdoc
     */
    public function postProcess(string $fixtureId, $object): void
    {
        // do nothing
    }
}