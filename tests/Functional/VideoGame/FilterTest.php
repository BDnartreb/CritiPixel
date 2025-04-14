<?php

declare(strict_types=1);

namespace App\Tests\Functional\VideoGame;

use App\Model\Entity\Tag;
use App\Tests\Functional\FunctionalTestCase;
use Doctrine\ORM\EntityManagerInterface;

final class FilterTest extends FunctionalTestCase
{
    private EntityManagerInterface $em;

    public function testShouldListTenVideoGames(): void
    {
        $this->get('/');
        self::assertResponseIsSuccessful();
        self::assertSelectorCount(10, 'article.game-card');
        $this->client->clickLink('2');
        self::assertResponseIsSuccessful();
    }

    public function testShouldFilterVideoGamesBySearch(): void
    {
        $this->get('/');
        self::assertResponseIsSuccessful();
        self::assertSelectorCount(10, 'article.game-card');
        $this->client->submitForm('Filtrer', ['filter[search]' => 'JeuTest0'], 'GET');
        self::assertResponseIsSuccessful();
        self::assertSelectorCount(1, 'article.game-card');
    }

    public function testShouldListArticlesForNoTagSelected(): void
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertCheckboxNotChecked('filter[tags][]');
        self::assertSelectorCount(10, 'article.game-card');
        //echo $this->client->getResponse()->getContent();
    }

    public function testShouldFilterGamesByOneTag(): void
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Filtrer')->form();

        $container = $this->client->getContainer();
        $this->em = $container->get('doctrine')->getManager();
        $tags = $this->em->getRepository(Tag::class)->findAll();
   
        $form['filter[tags][1]'] = $tags[1]->getId();
        $this->client->submit($form);
        $this->assertResponseIsSuccessful();
        self::assertSelectorCount(3, 'article.game-card');
        //echo $this->client->getResponse()->getContent();
    }

    public function testShouldFilterGamesWithMultipleTags(): void
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Filtrer')->form();
        $container = $this->client->getContainer();
        $this->em = $container->get('doctrine')->getManager();
        $tags = $this->em->getRepository(Tag::class)->findAll();
        $form['filter[tags][1]'] = $tags[1]->getId();
        $form['filter[tags][2]'] = $tags[2]->getId();
        $this->client->submit($form);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorCount(2, 'article');
        //echo $this->client->getResponse()->getContent();
    }
}