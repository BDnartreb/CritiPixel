<?php

namespace App\Doctrine\DataFixtures;

use App\Model\Entity\Review;
use App\Model\Entity\Tag;
use App\Model\Entity\User;
use App\Model\Entity\VideoGame;
use App\Rating\CalculateAverageRating;
use App\Rating\CountRatingsPerValue;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

final class DataFixtures extends Fixture
{
    public function __construct(
        private readonly Generator $faker,
        private readonly CalculateAverageRating $calculateAverageRating,
        private readonly CountRatingsPerValue $countRatingsPerValue,
    )
    {
        $calculateAverageRating = $this->calculateAverageRating;
        $countRatingsPerValue = $this->countRatingsPerValue;
    }

    public function load(ObjectManager $manager) : void
    {
        //TAGS
        $tagNames = ["Action", "platformer","shooter-fps", "Tactical"];
            // ,"dungeon-crawler", "open-world", "adventure", "puzzle",
            // "simulation", "driving", "farming", "strategy", "racing",
            // "sport", "rhythm", "horror", "educational", "narrative"]
        
        $tags = [];       
        foreach ($tagNames as $name) {
            $tag = new Tag();
            $tag->setName($name);
            $manager->persist($tag);
            $tags[] = $tag;
        }

        //USER TEST
        $userTest = new User();
        $userTest->setEmail('usertest@email.com');
        $userTest->setPlainPassword('password');
        $userTest->setUsername('usertest');
        $manager->persist($userTest);
        $manager->flush();

        //$tags = $manager->getRepository(Tag::class)->findAll();

        // VIDEOGAMES
        for ($i = 0; $i < 10; $i++){
            $videoGameTest = new VideoGame();
            $videoGameTest->setTitle(sprintf('JeuTest%d', $i));
            $videoGameTest->setImageName('video_game_' . $i . '.png');
            $videoGameTest->setImageSize(2541524);
            $videoGameTest->setDescription('Descritpion du Jeu ' . $i);
            $videoGameTest->setReleaseDate(new DateTimeImmutable());
            $videoGameTest->setTest('Test ' . $i);
            $videoGameTest->setRating(random_int(1, 5));
            $manager->persist($videoGameTest);
        }
        $manager->flush();

        $videoGames = [];
        $videoGames = $manager->getRepository(VideoGame::class)->findAll();

        // VideoGame reviewed by userTest with tag[1]
        $videoGame1 = $videoGames[1];
        $reviewTest = new Review();
        $reviewTest->setVideoGame($videoGame1);
        $reviewTest->setUser($userTest);
        $reviewTest->setRating(5);
        $reviewTest->setComment('Commentaire Review Test');
        $manager->persist($reviewTest);
        $videoGameTest->getReviews()->add($reviewTest);
        $videoGame1->getTags()->add($tags[1]);
        $manager->persist($videoGame1);

        // VideoGame No reviewed with tag[1] and tag[2]
        $videoGame2 = $videoGames[2];
        $videoGame2->getTags()->add($tags[1]);
        $videoGame2->getTags()->add($tags[2]);
        $manager->persist($videoGame2);

        // VideoGame No reviewed with tag[1], tag[2] and tag[3]
        $videoGame3 = $videoGames[3];
        $videoGame3->getTags()->add($tags[1]);
        $videoGame3->getTags()->add($tags[2]);
        $videoGame3->getTags()->add($tags[3]);
        $manager->persist($videoGame3);

        // VIDEOGAMES
        $videoGames = []; 
        for ($i = 0; $i < 50; $i++) {
            $videoGame = new VideoGame();
            $videoGame->setTitle('Jeu vidéo ' . $i);
            $videoGame->setImageName('video_game_' . $i . '.png');
            $videoGame->setImageSize(2541524);
            $videoGame->setDescription($this->faker->paragraphs(10, true));
            $videoGame->setReleaseDate(new DateTimeImmutable());
            $videoGame->setTest('Test ' . $i);
            $videoGame->setRating(random_int(1, 5));
            $manager->persist($videoGame);
            //$videoGames [] = $videoGame;
        }     

        $manager->flush();
    }
}