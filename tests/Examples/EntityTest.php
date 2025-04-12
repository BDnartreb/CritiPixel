<?php
namespace App\Tests\Unit;

use App\Entity\EntityName;
use App\Entity\User;
use App\Entity\Mark; 
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EntityNameTest extends KernelTestCase
{
    public function getEntity(): EntityName
    {
        return (new EntityName())
            ->setAttribute1('blabla')
            ->setAttribute2('toto');
    }

    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $entity = $this->getEntity();

        $errors = $container->get('validator')->validate($entity);
        $this->assertCount(0, $errors);
    }

    public function tesInvalidAttribute1()
    {
        self::bootKernel();
        $container = static::getContainer();
        $entity = $this->getEntity();
        $entity->setAttribute1(''); // Attribute1 not blanck et min lenght 2 characters

        $errors = $container->get('validator')->validate($entity);
        $this->assertCount(2, $errors);
    }

    public function testGetAverage()
    {
        $entity = $this->getEntity();
        $user = static::getContainer()->get('doctrine.orm.entity.manager')->find(User::class, 1);
    
        // create marks to calculate average
        for ($i = 0; $i < 5; $i++) {
            $mark = new Mark();
            $mark->setMark(2) // 5 times mark 2 so average is 2.0 (float)
                ->setUser($user)
                ->setAttribute1($attribute1);
            $entity->addMark($mark);
        }
        $this->assertTrue(2.0 === $entity->getAverage());//expected average is a float
    }
}



