<?php

namespace Ais\WisudaBundle\Tests\Fixtures\Entity;

use Ais\WisudaBundle\Entity\Wisuda;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadWisudaData implements FixtureInterface
{
    static public $wisudas = array();

    public function load(ObjectManager $manager)
    {
        $wisuda = new Wisuda();
        $wisuda->setTitle('title');
        $wisuda->setBody('body');

        $manager->persist($wisuda);
        $manager->flush();

        self::$wisudas[] = $wisuda;
    }
}
