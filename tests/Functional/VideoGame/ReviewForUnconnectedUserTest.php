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

    public function testShouldNotDisplayReviewFormToUnconnectedUser(): void
    {
        $this->get('/jeutest0');
        self::assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'JeuTest0');
        $this->assertSelectorNotExists('form');
    }

    public function testUnconnectedUserNotAuthororizedToPostAReview(): void
    {
        $this->client->request('POST', '/jeutest0', [
            'review' => [
                'rating' => '3',
                'comment' => 'Tentative sans être connecté',
            ]
        ]);

        $this->assertResponseStatusCodeSame(422);
    }

}
