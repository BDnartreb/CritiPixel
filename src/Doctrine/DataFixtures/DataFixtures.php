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
        $tags = [
            ["Action"], ["platformer"],["shooter-fps"], ["Tactical"] 
            // ,["dungeon-crawler"], ["open-world"],
            // ["adventure"], ["puzzle"],["simulation"], ["driving"], ["farming"], ["strategy"], ["racing"],
            // ["sport"], ["rhythm"], ["horror"], ["educational"], ["narrative"]
        ];            

        foreach ($tags as $tag) {
            $newtag = new Tag();
            $newtag->setName($tag[0]);
            $manager->persist($newtag);
        }

    //USERS
        for ($i = 0; $i <10; $i++) {
            $user = new User();
            $user->setEmail(sprintf('user%d@email.com', $i));
            $user->setPlainPassword('password');
            $user->setUsername(sprintf('user%d', $i));
            $manager->persist($user);
        }
        $manager->flush();

        $tags = $manager->getRepository(Tag::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();

    // VIDAOGAMES
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
            $videoGames [] = $videoGame;
        }     
    
    // REVIEWS
        foreach ($videoGames as $videoGame) {
            $uniq = random_int(0, 5); // A user can give only one rating for each videogame
            for ($i = 0; $i < random_int(1, 5); $i++){    
                $review = new Review();
                $review->setVideoGame($videoGame);
                
                $review->setUser($users[$uniq]);
                $uniq++;
                $review->setRating(random_int(1, 5));
                $review->setComment($this->faker->paragraphs(10, true));
                $manager->persist($review);
                $videoGame->getReviews()->add($review);
            }

        // TAGS
            for ($j = 0; $j < random_int(1, 4); $j++){
                $videoGame->getTags()->add($tags[random_int(1, count($tags)-1)]);    
            }
                    
            $manager->persist($review);
            $manager->persist($videoGame);
            $this->calculateAverageRating->calculateAverage($videoGame);
            $this->countRatingsPerValue->countRatingsPerValue($videoGame);
        }

            //////////////////////////////////////////
        //USER TEST
            $userTest = new User();
            $userTest->setEmail('usertest@email.com');
            $userTest->setPlainPassword('password');
            $userTest->setUsername('usertest');
            $manager->persist($userTest);
            $manager->flush();

            // VIDAOGAMES
            $videoGameTest0 = new VideoGame();
            $videoGameTest0->setTitle('Jeu test noreview');
            $videoGameTest0->setImageName('video_game_0.png');
            $videoGameTest0->setImageSize(2541524);
            $videoGameTest0->setDescription('Descritpion du Jeu vidéo test noreview');
            $videoGameTest0->setReleaseDate(new DateTimeImmutable());
            $videoGameTest0->setTest('Test Noreview');
            $videoGameTest0->setRating(3);     
            $manager->persist($videoGameTest0);

            $videoGameTest = new VideoGame();
            $videoGameTest->setTitle('Jeu test review');
            $videoGameTest->setImageName('video_game_0.png');
            $videoGameTest->setImageSize(2541524);
            $videoGameTest->setDescription('Descritpion du Jeu vidéo test review');
            $videoGameTest->setReleaseDate(new DateTimeImmutable());
            $videoGameTest->setTest('Test Review');
            $videoGameTest->setRating(4);     

            // REVIEWS
            $reviewTest = new Review();
            $reviewTest->setVideoGame($videoGameTest);
            $reviewTest->setUser($userTest);
            $reviewTest->setRating(5);
            $reviewTest->setComment('Commentaire Review Test');
            $manager->persist($reviewTest);
            $videoGameTest->getReviews()->add($reviewTest);

            // TAGS
            $videoGameTest->getTags()->add($tags[1]);    
                        
            $manager->persist($reviewTest);
            $manager->persist($videoGameTest);

            $this->calculateAverageRating->calculateAverage($videoGameTest);
            $this->countRatingsPerValue->countRatingsPerValue($videoGameTest);


        $manager->flush();
    }
}