<?php

namespace App\Tests;

use App\Model\Entity\NumberOfRatingPerValue;
use App\Model\Entity\Review;
use App\Model\Entity\VideoGame;
use App\Rating\RatingHandler;
use PHPUnit\Framework\TestCase;

class NumberOfRatingsPerValueTest extends TestCase
{
    //#[DataProvider('provideVideoGame')]
    /**
    * @dataProvider provideNumberOfRatingsPerValue
    */
    public function testNumberOfRatingsPerValue(
        VideoGame $videoGame,
        NumberOfRatingPerValue $expectedNumberOfRatingPerValue
        ): void
    {
        //count the number of ratings per value
        $ratingHandler = new RatingHandler();
        $ratingHandler->countRatingsPerValue($videoGame);
        
        //compare the expected result to the result calculated by countRatingsPerValue()
        self::assertEquals($expectedNumberOfRatingPerValue, $videoGame->getNumberOfRatingsPerValue());
    }

    public static function provideNumberOfRatingsPerValue()
    {
        $videoGameWithOneRating = new VideoGame();
        $videoGameWithOneRating->getReviews()->add((new Review())->setRating(3));
        $expectedNumberOfRatingForOneRating = new NumberOfRatingPerValue();
        $expectedNumberOfRatingForOneRating->increaseThree();

        $videoGameWithRatings = new VideoGame();
        $ratings = [1,2,3,3,3,4,4,5];
        foreach ($ratings as $rating){
            $videoGameWithRatings->getReviews()->add((new Review())->setRating($rating));
        }

        $expectedNumberOfRatingsPerValue = new NumberOfRatingPerValue();
        $expectedNumberOfRatingsPerValue->increaseOne();
        $expectedNumberOfRatingsPerValue->increaseTwo();
        $expectedNumberOfRatingsPerValue->increaseThree();
        $expectedNumberOfRatingsPerValue->increaseThree();
        $expectedNumberOfRatingsPerValue->increaseThree();
        $expectedNumberOfRatingsPerValue->increaseFour();
        $expectedNumberOfRatingsPerValue->increaseFour();
        $expectedNumberOfRatingsPerValue->increaseFive();


        return [
            'No rating Per Value' => [new VideoGame(), new NumberOfRatingPerValue()],
            'One rating Per Value' => [$videoGameWithOneRating, $expectedNumberOfRatingForOneRating],
            'Ratings Per Value' => [$videoGameWithRatings, $expectedNumberOfRatingsPerValue],
        ];
    }
}


// /**
//      * @dataProvider provideVideoGame
//      */
//     public function testShouldCountRatingPerValue(VideoGame $videoGame, NumberOfRatingPerValue $expectedNumberOfRatingPerValue): void
//     {
//         $ratingHandler = new RatingHandler();
//         $ratingHandler->countRatingsPerValue($videoGame);
//         self::assertEquals($expectedNumberOfRatingPerValue, $videoGame->getNumberOfRatingsPerValue());
//     }

//     /**
//      * @return iterable<array{VideoGame, NumberOfRatingPerValue}>
//      */
//     public static function provideVideoGame(): iterable
//     {
//         yield 'No review' => [
//             new VideoGame(),
//             new NumberOfRatingPerValue(),
//         ];
//         yield 'One review' => [
//             self::createVideoGame(5),
//             self::createExpectedState(five: 1),
//         ];
//         yield 'A lot of reviews' => [
//             self::createVideoGame(1, 2, 2, 3, 3, 3, 4, 4, 4, 4, 5, 5, 5, 5, 5),
//             self::createExpectedState(1, 2, 3, 4, 5),
//         ];
//     }

//     private static function createVideoGame(int ...$ratings): VideoGame
//     {
//         $videoGame = new VideoGame();
//         foreach ($ratings as $rating) {
//             $videoGame->getReviews()->add((new Review())->setRating($rating));
//         }
//         return $videoGame;
//     }

//     private static function createExpectedState(int $one = 0, int $two = 0, int $three = 0, int $four = 0, int $five = 0): NumberOfRatingPerValue
//     {
//         $state = new NumberOfRatingPerValue();
//         for ($i = 0; $i < $one; ++$i) {
//             $state->increaseOne();
//         }
//         for ($i = 0; $i < $two; ++$i) {
//             $state->increaseTwo();
//         }
//         for ($i = 0; $i < $three; ++$i) {
//             $state->increaseThree();
//         }
//         for ($i = 0; $i < $four; ++$i) {
//             $state->increaseFour();
//         }
//         for ($i = 0; $i < $five; ++$i) {
//             $state->increaseFive();
//         }
//         return $state;
//     }