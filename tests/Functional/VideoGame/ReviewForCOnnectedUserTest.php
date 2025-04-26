<?php

namespace App\Tests;

use App\Model\Entity\Review;
use App\Model\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ReviewForConnectedUserTest extends WebTestCase
//class CreateReviewTest extends FunctionalTestCase
{
    private EntityManagerInterface $em;
    private string $slug;
    private string $title;
    private string $slug1;
    private string $title1;
    private string $email;
    protected KernelBrowser $client;

    public function setUp(): void
    {
        $this->slug = 'jeutest0';
        $this->title = 'JeuTest0';
        $this->slug1 = 'jeutest1';
        $this->title1 = 'JeuTest1';
        $this->email = 'usertest@email.com';

        $this->client = static::createClient(); //https://symfony.com/doc/current/testing.html / Logging in Users (Authentication)
        $container = static::getContainer();
        $this->em = $container->get('doctrine')->getManager();
        $userRepository = $this->em->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $this->email]);  
        $this->client->loginUser($user);
    }

    public function testShouldDisplayReviewFormToUser(): void
    {
        $crawler = $this->client->request('GET', '/' . $this->slug);
        self::assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $this->title);
        $this->assertSelectorExists('form');
    }

    public function testShouldNotDisplayReviewFormToUserAlreadyReviewedTheGame(): void
    {
        $crawler = $this->client->request('GET', '/' . $this->slug1);
        self::assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $this->title1);
        $this->assertSelectorNotExists('form');
    }

    /**
    * @dataProvider provideValidReviewData
    */
    public function testShouldAddAReviewWithValidDataSuccessfully(string $rating, string $comment): void
    {
        // Check the right game is displayed
        $crawler = $this->client->request('GET', '/' . $this->slug);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $this->title);
        //echo $this->client->getResponse()->getContent();

        // Fill the form
        $form = $crawler->selectButton('Poster')->form();
        $form["review[rating]"]= $rating;
        $form["review[comment]"]= $comment;
        $this->client->submit($form);

        // check redirect   
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND); // $this->assertResponseStatusCodeSame(302);
        $this->client->followRedirect();
        $this->assertEquals('/' . $this->slug, $this->client->getRequest()->getPathInfo());
        $this->assertSelectorTextContains('h1', $this->title);
                //$this->assertRouteSame('video_game_show', ['slug' => $this->slug]);
                //$this->assertRouteSame('video_game_show');
                //$this->assertStringEndsWith('/jeutest0', $this->client->getRequest()->getRequestUri());
        
        // Check rating and comment are in the database
        $newReview = $this->em->getRepository(Review::class)->findOneBy(['rating' => $rating]);
        $this->assertNotNull($newReview);
        $this->assertEquals($rating, $newReview->getRating());
        $this->assertEquals($comment, $newReview->getComment());
        $this->em->remove($newReview);
        $this->em->flush();
    }

    /**
    * @return array<int, array{string, string}>
    */
    public function provideValidReviewData(): array
    {
        return [
            ['3', 'Blabla'],
            ['4', ''],
        ];
    }

    /**
    * @dataProvider provideInvalidReviewData
    */
    public function testShouldNotAddAReviewWithInValidData(string $rating, string $comment): void
    {
        $crawler = $this->client->request('POST', '/' . $this->slug, [
            'review' => [
                'rating' => $rating,
                'comment' => $comment,
            ]
        ]);

        $this->assertResponseStatusCodeSame(422);
    }

    /**
    * @return array<int, array{string, string}>
    */
    public function provideInvalidReviewData(): array
    {
        return [
            ['0', 'Note not between 1 and 5'],
            ['6', 'Note not between 1 and 5'],
            ['', 'No note'],
            ['3', str_repeat('a', 70000)], // Too long comment
        ];
    }
}
