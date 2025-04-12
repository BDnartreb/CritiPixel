<?php

namespace App\Tests;

use App\Model\Entity\Review;
use App\Model\Entity\User;
use App\Model\Entity\VideoGame;
use App\Tests\Functional\FunctionalTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class VideoGameTest extends WebTestCase
{
    private EntityManagerInterface $em;
    private KernelBrowser $client;
    private $form;
    private $slug;
    private $title;
    private $newReview;


    public function setUp(): void
    {
        $this->slug = '/jeu-test-noreview';
        $this->title = 'Jeu test noreview';
        $this->client = static::createClient();
        //https://symfony.com/doc/current/testing.html / Logging in Users (Authentication)
        $container = $this->client->getContainer();
        $this->em = $container->get('doctrine')->getManager();
        $userRepository = $this->em->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => 'usertest@email.com']);        
        $this->client->loginUser($user);
        
        // Go to the the review page and check the right page is displayed
        $crawler = $this->client->request('GET', $this->slug);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $this->title);

        // Check the form is displayed
        $this->assertSelectorTextContains('button', 'Poster');

        // get the form
        $this->form = $crawler->selectButton('Poster')->form();
    }

    public function testShouldAddAReviewWithValidDataSuccessfully(): void
    {
        // add valid data (rating and comment)
        $this->form["review[rating]"]= "3";
        $this->form["review[comment]"]= "blabla";
        $this->client->submit($this->form);

        // check redirect
        // $this->assertResponseStatusCodeSame(302);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->client->followRedirect();
        $this->assertRouteSame('video_game_show', ['slug' =>  $this->slug]);
        // $this->assertRouteSame('video_game_show', ['slug' => $videoGame->getSlug()]);
        
        // Check rating and comment are in the database
        $reviewRepository = $this->em->getRepository(Review::class);
        $this->newReview = $reviewRepository->findOneBy(['comment' => 'blabla']);
        $this->assertNotNull($this->newReview);
        $this->assertEquals('3', $this->newReview->getRating());
        $this->assertEquals('blabla', $this->newReview->getComment());

        // Check the review form of this game is not displayed anymore for this user
        $crawler = $this->client->request('GET', $this->slug);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $this->title);
        $this->assertSelectorNotExists('form');
    }

    function testShouldNotAddAReviewBecauseOfInvalidData(): void
    {
        $this->form["review[rating]"]= "";
        $this->client->submit($this->form);

        // check redirect
        // $this->assertResponseStatusCodeSame(422);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertResponseIsSuccessful();
    }

    protected function tearDown(): void
    {
        if ($this->newReview !== null) {
            $this->em->remove($this->newReview);
            $this->em->flush();
        }
    }
        


    // Review form not displayed for unconnected user

    // Review form is not displayed for connected user how has already given de review for this game


}
