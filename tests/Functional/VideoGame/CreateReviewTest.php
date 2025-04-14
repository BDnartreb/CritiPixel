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

class CreateReviewTest extends WebTestCase
//class CreateReviewTest extends FunctionalTestCase
{
    private EntityManagerInterface $em;
    private KernelBrowser $client;

    public function testShouldAddAReviewWithValidDataSuccessfully(): void
    {
        $slug = 'jeutest0';
        $title = 'JeuTest0';
        $client = static::createClient();
        //https://symfony.com/doc/current/testing.html / Logging in Users (Authentication)
        $container = static::getContainer();
        $this->em = $container->get('doctrine')->getManager();
        $userRepository = $this->em->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => 'usertest@email.com']);  
        $client->loginUser($user);
               
        // Go to the the review page and check the right page is displayed
        $crawler = $client->request('GET', '/' . $slug);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $title);
        //echo $this->client->getResponse()->getContent();
        $form = $crawler->selectButton('Poster')->form();
        $form["review[rating]"]= "3";
        $form["review[comment]"]= "blabla";
        $client->submit($form);

    // check redirect   
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND); // $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertEquals('/jeutest0', $client->getRequest()->getPathInfo());
        $this->assertSelectorTextContains('h1', 'JeuTest0');
        //$this->assertRouteSame('video_game_show', ['slug' => $this->slug]);
        //$this->assertRouteSame('video_game_show');
        //$this->assertStringEndsWith('/jeutest0', $this->client->getRequest()->getRequestUri());
        
    // Check rating and comment are in the database
        $newReview = new Review();
        // $reviewRepository = $this->em->getRepository(Review::class);
        // $newReview = $reviewRepository->findOneBy(['comment' => 'blabla']);
        $newReview = $this->em->getRepository(Review::class)->findOneBy(['comment' => 'blabla']);
        $this->assertNotNull($newReview);
        $this->assertEquals('3', $newReview->getRating());
        $this->assertEquals('blabla', $newReview->getComment());
        $this->em->remove($newReview);
        $this->em->flush();
    }
}
















    // public function testShouldAddAReviewWithValidDataSuccessfully(): void
    // {
    //     $this->slug = 'jeutest0';
    //     $this->title = 'JeuTest0';
    //     $this->client = static::createClient();
    //     //https://symfony.com/doc/current/testing.html / Logging in Users (Authentication)
    //     $container = static::getContainer();
    //     $this->em = $container->get('doctrine')->getManager();
    //             $userRepository = $this->em->getRepository(User::class);
    //             $user = $userRepository->findOneBy(['email' => 'usertest@email.com']);  
    //             $this->client->loginUser($user);
               
    //             // Go to the the review page and check the right page is displayed
    //             $crawler = $this->client->request('GET', '/' . $this->slug);
    //             $this->assertResponseIsSuccessful();
    //             $this->assertSelectorTextContains('h1', $this->title);
        
    //     //        echo $this->client->getResponse()->getContent();
               
    //             // Check the form is displayed
    //      //       $this->assertSelectorExists('button');
    //             $this->form = $crawler->selectButton('Poster')->form();
    //     // add valid data (rating and comment)
    //     $this->form["review[rating]"]= "3";
    //     $this->form["review[comment]"]= "blabla";
    //     $this->client->submit($this->form);

    //     // check redirect
    //     // $this->assertResponseStatusCodeSame(302);
    //     $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    //     $this->client->followRedirect();
    //     //$this->assertRouteSame('video_game_show', ['slug' => $this->slug]);
    //     //$this->assertRouteSame('video_game_show');
    //     $this->assertEquals('/jeutest0', $this->client->getRequest()->getPathInfo());
    //     //$this->assertStringEndsWith('/jeutest0', $this->client->getRequest()->getRequestUri());
    //     $this->assertSelectorTextContains('h1', 'JeuTest0');

    //     //$this->assertRouteSame('video_game_show', ['slug' =>  $this->slug]);
    //     // $this->assertRouteSame('video_game_show', ['slug' => $videoGame->getSlug()]);
        
    //     // Check rating and comment are in the database
    //     $reviewRepository = $this->em->getRepository(Review::class);
    //     $this->newReview = $reviewRepository->findOneBy(['comment' => 'blabla']);
    //     $this->assertNotNull($this->newReview);
    //     $this->assertEquals('3', $this->newReview->getRating());
    //     $this->assertEquals('blabla', $this->newReview->getComment());

    //     // Check the review form of this game is not displayed anymore for this user
        // $crawler = $this->client->request('GET', $this->slug);
        // $this->assertResponseIsSuccessful();
        // $this->assertSelectorTextContains('h1', $this->title);
        // $this->assertSelectorNotExists('form');
//    }

 

