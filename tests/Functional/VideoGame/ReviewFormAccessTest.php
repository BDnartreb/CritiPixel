<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReviewFormAccessTest extends WebTestCase
{
    private EntityManagerInterface $em;
    private KernelBrowser $client;

    public function testShouldNotDisplayFromForUnconnectedUser(): void
    {
        $this->client = static::createClient();
        //https://symfony.com/doc/current/testing.html / Logging in Users (Authentication)
        $container = $this->client->getContainer();
        $this->em = $container->get('doctrine')->getManager();
        
        // Go to the the review page and check the right page is displayed
        $crawler = $this->client->request('GET', '/jeu-test-noreview');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Jeu test noreview');
        $this->assertSelectorNotExists('form');
    }
}
