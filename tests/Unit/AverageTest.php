<?php

namespace App\Tests;

use App\Model\Entity\Review;
use App\Model\Entity\VideoGame;
use App\Rating\RatingHandler;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class AverageTest extends TestCase
{
    //#[DataProvider('provideVideoGame')]
    /**
    * @dataProvider provideVideoGame
    */
    public function testAverage(VideoGame $videoGame, ?int $expectedAverageRating): void
    {
        //calculate the average rating of the videoGame
        $ratingHandler = new RatingHandler();
        $ratingHandler->calculateAverage($videoGame);

        //compare the expected result of the average to the average rating of the videoGame calculated by calculateAverage()
        self::assertSame($expectedAverageRating, $videoGame->getAverageRating());
    }

    /**
    * @return iterable<array{VideoGame, ?int}>
    */
    public static function provideVideoGame()
    {
        $oneRating = new VideoGame();
        $oneRating->getReviews()->add((new Review())->setRating(3));
 
        $severalRatings = new VideoGame();
        $ratings = [1,2,3,3,3,4,4,5];
        foreach ($ratings as $rating){
            $severalRatings->getReviews()->add((new Review())->setRating($rating));
        }

        //return a VideoGame Object and an expectedAverageRating
        return [
            'no rating' => [new VideoGame(), null],
            'one rating' => [$oneRating, 3],
            'several ratings' => [$severalRatings, 4],
        ];

    }

}


    // /**
    // * @dataProvider provideVideoGame
    // */
    // public function testAverage(VideoGame $videoGame, ?int $expectedAverageRating): void
    // {
    //     //calculate the average rating of the videoGame
    //     $ratingHandler = new RatingHandler();
    //     $ratingHandler->calculateAverage($videoGame);

    //     //compare the right result of the average to the average rating of the videoGame calculated by calculateAverage()
    //     self::assertSame($expectedAverageRating, $videoGame->getAverageRating());
    // }

    // /**
    // * @return iterable<array{VideoGame, ?int}>
    // */
    // public static function provideVideoGame(): iterable
    // {
    //     //Case no rating
    //     yield 'No review' => [new VideoGame(), null,];
    //     //Case 1 rating
    //     yield 'One review' => [self::createVideoGame(5), 5,];
    //     //Case several ratings
    //     yield 'A lot of reviews' => [
    //         self::createVideoGame(1, 2, 2, 3, 3, 3, 4, 4, 4, 4, 5, 5, 5, 5, 5),
    //         4,
    //     ];
    // }

    // //create de videoGame object with severale ratings ...$ratings
    // private static function createVideoGame(int ...$ratings): VideoGame
    // {
    //     $videoGame = new VideoGame();

    //     foreach ($ratings as $rating) {
    //         $videoGame->getReviews()->add((new Review())->setRating($rating));
    //     }

    //     return $videoGame;
    // }
