<?php

declare(strict_types=1);

namespace App\Tests\Functional\VideoGame;

use App\Model\Entity\VideoGame;
use App\Tests\Functional\FunctionalTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

final class FilterTest extends FunctionalTestCase
{
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
        $this->client->submitForm('Filtrer', ['filter[search]' => 'Jeu test noreview'], 'GET');
        self::assertResponseIsSuccessful();
        self::assertSelectorCount(1, 'article.game-card');
    }

    // /**
    // * @dataProvider provideVideoGamesAndTags
    // */

    public function testShouldFilterVideoGamesByTag(): void
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        //echo $crawler->filter('button')->text();
        //echo $this->client->getResponse()->getContent();
        // Check the form is displayed
        //$this->assertSelectorTextContains('form button[type="submit"]', 'Filtrer');
       // $this->assertSelectorTextContains('button', 'Filtrer');

        $this->assertCheckboxNotChecked('filter[tags][]');
//        $this->assertResponseIsSuccessful();
  //      echo $this->client->getResponse()->getContent();
        $this->assertSelectorExists('article');
    }

    public function testShouldFilterGamesWithOneTag(): void
    {
        //$client = static::createClient();
        $crawler = $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Filtrer')->form();

        // foreach ($form->all() as $field) {
        //     echo $field->getName() . "\n";
        // }

        $form['filter[tags][1]'] = '542';
        $this->client->submit($form);
        $this->assertResponseIsSuccessful();
        // Vérifie que les résultats affichés correspondent (tu peux affiner selon ton contenu)
        $this->assertSelectorExists('article');
        echo $this->client->getResponse()->getContent();
    }

    public function testShouldFilterGamesWithMultipleTags(): void
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Filtrer')->form();

        $form['filter[tags][10]'] = '551';
        $form['filter[tags][14]'] = '555';

        $this->client->submit($form);
        $this->assertResponseIsSuccessful();

        //$this->assertSelectorExists(count('article'), 2);
        echo $this->client->getResponse()->getContent();
        $this->assertSelectorCount(1, 'article');
    }






    //     // get the form
    //     $submitbutton = $crawler->selectButton('Filtrer');
    //     $form = $submitbutton->form();
    //     $form["filter[tags][]"]="Action";

        




    //     self::assertSelectorCount(10, 'article.game-card');
    //     $this->client->submitForm('Filtrer', ['filter[search]' => 'Jeu test noreview'], 'GET');
    //     self::assertResponseIsSuccessful();
    //     self::assertSelectorCount(1, 'article.game-card');
    // }

    // public static function provideVideoGamesAndTags()
    // {
    //     $oneTag = new VideoGame();
    //     $oneTag->getTags('Action');

    //     $severalTags = new VideoGame();
    //     $severalTags->getTags('Action');
    //     $severalTags->getTags('platformer');
    //     $severalTags->getTags('Tactical');

    //     return [
    //         'no tag' => [new VideoGame(), null],
    //         'one tag' => [$oneTag, 'Action'],
    //         'several tags' => [$severalTags, ],
    //     ];
    // }


    // $tags = [
    //     ["Action"], ["platformer"],["shooter-fps"], ["Tactical"] 
    //     // ,["dungeon-crawler"], ["open-world"],
    //     // ["adventure"], ["puzzle"],["simulation"], ["driving"], ["farming"], ["strategy"], ["racing"],
    //     // ["sport"], ["rhythm"], ["horror"], ["educational"], ["narrative"]
    // ];

}


 


    // public static function provideVideoGamesAndTags()
    // {
    //     $oneRating = new VideoGame();
    //     $oneRating->getReviews()->add((new Review())->setRating(3));
 
    //     $severalRatings = new VideoGame();
    //     $ratings = [1,2,3,3,3,4,4,5];
    //     foreach ($ratings as $rating){
    //         $severalRatings->getReviews()->add((new Review())->setRating($rating));
    //     }

    //     //return a VideoGame Object and an expectedAverageRating
    //     return [
    //         'no rating' => [new VideoGame(), null],
    //         'one rating' => [$oneRating, 3],
    //         'several ratings' => [$severalRatings, 4],
    //     ];

    // }






// • le filtrage avec différents tags. 
// • le comportement lorsque l'utilisateur spécifie un tag qui n'existe pas. 
// • Il est recommandé d'utiliser le “DataProvider” pour couvrir tous les cas de test, notamment dans le cas du test fonctionnel sur le filtrage :  
// ◦ aucun tag n'est spécifié,  
// ◦ un seul tag est utilisé,  
// ◦ plusieurs tags sont appliqués à un jeu vidéo.  