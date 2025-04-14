<?php

namespace App\Tests;

use App\Model\Entity\User;
use App\Tests\Functional\FunctionalTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

//class ReviewFormAccessTest extends WebTestCase
class ReviewFormAccessTest extends FunctionalTestCase
{
    private EntityManagerInterface $em;
    //protected KernelBrowser $client;

    public function testShouldNotDisplayFromToUnconnectedUser(): void
    {
        $this->get('/jeutest0');
        self::assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'JeuTest0');
        $this->assertSelectorNotExists('form');
    }

    public function testShouldDisplayFromToConnectedUser(): void
    {
        $container = $this->getContainer();
        $this->em = $container->get('doctrine')->getManager();
        $userRepository = $this->em->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => 'usertest@email.com']);  
        $this->client->loginUser($user);

        $this->get('/jeutest0');
        self::assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'JeuTest0');
        $this->assertSelectorExists('form');
    }

    public function testShouldNotDisplayFromToUserAlreadyReviewedTheGame(): void
    {
        $container = $this->getContainer();
        $this->em = $container->get('doctrine')->getManager();
        $userRepository = $this->em->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => 'usertest@email.com']);  
        $this->client->loginUser($user);

        $this->get('/jeutest1');
        self::assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'JeuTest1');
        $this->assertSelectorNotExists('form');
    }
}
