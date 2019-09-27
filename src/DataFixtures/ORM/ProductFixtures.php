<?php

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;
/**
 * Class AccountFixtures
 *
 * @package App\DataFixtures
 */
class ProductFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $loader = new NativeLoader();
        $objectSet = $loader->loadFile(__DIR__ . '/fixtures/products.yaml')->getObjects();
        foreach($objectSet as $object)
        {
            $manager->persist($object);
        }
 
        $manager->flush();
    }
    
}
