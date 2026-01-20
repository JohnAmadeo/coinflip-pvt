<?php

declare(strict_types=1);

namespace Coinflip\Tests;

use Coinflip\Randomness;
use PHPUnit\Framework\TestCase;

use function Coinflip\flip;

class FlipTest extends TestCase
{
    /**
     * Test that flip() returns an empty array when times is 0.
     */
    public function testFlipReturnsEmptyArrayForZero(): void
    {
        $result = flip(0);
        $this->assertIsArray($result);
        $this->assertEmpty($result);
        $this->assertCount(0, $result);
    }

    /**
     * Test that flip() returns an array with the correct number of results.
     */
    public function testFlipReturnsCorrectNumberOfResults(): void
    {
        $testCases = [1, 5, 10, 100];

        foreach ($testCases as $times) {
            $result = flip($times);
            $this->assertIsArray($result);
            $this->assertCount($times, $result);
        }
    }

    /**
     * Test that flip() returns an array of booleans.
     */
    public function testFlipReturnsArrayOfBooleans(): void
    {
        $result = flip(50);

        foreach ($result as $value) {
            $this->assertIsBool($value);
        }
    }

    /**
     * Test that flip() throws InvalidArgumentException for negative times.
     */
    public function testFlipThrowsExceptionForNegativeTimes(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The number of flips must be non-negative');

        flip(-1);
    }

    /**
     * Test that flip() throws exception with descriptive message for large negative value.
     */
    public function testFlipThrowsExceptionWithDescriptiveMessage(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('got: -100');

        flip(-100);
    }

    /**
     * Test that flip() produces deterministic results when seeded.
     */
    public function testFlipProducesDeterministicResults(): void
    {
        // First run with seed
        Randomness::seed(54321);
        $results1 = flip(50);

        // Second run with same seed should produce identical results
        Randomness::seed(54321);
        $results2 = flip(50);

        $this->assertEquals($results1, $results2);
    }

    /**
     * Test that flip() handles large numbers of flips efficiently (performance test).
     */
    public function testFlipHandlesLargeNumbers(): void
    {
        $startTime = microtime(true);
        $result = flip(50000);
        $endTime = microtime(true);

        // Verify correct number of results
        $this->assertCount(50000, $result);

        // Verify all results are booleans
        foreach ($result as $value) {
            $this->assertIsBool($value);
        }

        // Verify execution time is reasonable (should be very fast)
        // Allow up to 1 second for 50k flips (this is very generous)
        $executionTime = $endTime - $startTime;
        $this->assertLessThan(1.0, $executionTime, "Flip took too long: {$executionTime}s");
    }

    /**
     * Test that flip() results have reasonable statistical distribution.
     * This verifies that is_lucky() is being called correctly.
     */
    public function testFlipStatisticalDistribution(): void
    {
        $result = flip(10000);
        $trueCount = count(array_filter($result, fn($v) => $v === true));
        $trueRatio = $trueCount / 10000;

        // With 10000 trials, the ratio should be close to 0.5
        // Allow for statistical variance
        $this->assertGreaterThan(0.485, $trueRatio);
        $this->assertLessThan(0.515, $trueRatio);
    }

    /**
     * Test that flip(1) returns exactly one result.
     */
    public function testFlipReturnsOneResultForOne(): void
    {
        $result = flip(1);
        $this->assertCount(1, $result);
        $this->assertIsBool($result[0]);
    }

    /**
     * Test that flip() results are indexed arrays (numeric keys starting from 0).
     */
    public function testFlipReturnsIndexedArray(): void
    {
        $result = flip(5);

        // Verify keys are 0, 1, 2, 3, 4
        $this->assertEquals([0, 1, 2, 3, 4], array_keys($result));
    }

    /**
     * Test that multiple calls to flip() with same seed produce identical results.
     */
    public function testMultipleFlipCallsWithSeedAreIdentical(): void
    {
        Randomness::seed(99999);
        $results1 = flip(20);

        Randomness::seed(99999);
        $results2 = flip(20);

        $this->assertSame($results1, $results2);
    }

    /**
     * Test that flip() can handle edge case of exactly 1 flip.
     */
    public function testFlipHandlesSingleFlip(): void
    {
        // Run multiple times to verify it consistently works
        for ($i = 0; $i < 10; $i++) {
            $result = flip(1);
            $this->assertCount(1, $result);
            $this->assertIsBool($result[0]);
        }
    }

    /**
     * Test that flip() calls is_lucky() the correct number of times.
     * This test verifies the behavior by checking deterministic sequences.
     */
    public function testFlipCallsIsLuckyCorrectNumberOfTimes(): void
    {
        // Use a seed that produces a known sequence
        Randomness::seed(42);

        // Get the expected sequence by calling is_lucky() directly
        $expected = [];
        for ($i = 0; $i < 10; $i++) {
            $expected[] = Randomness::is_lucky();
        }

        // Reset the seed and use flip()
        Randomness::seed(42);
        $actual = flip(10);

        // The sequences should match, proving flip() calls is_lucky() the right number of times
        $this->assertEquals($expected, $actual);
    }
}
