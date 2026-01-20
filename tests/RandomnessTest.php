<?php

declare(strict_types=1);

namespace Coinflip\Tests;

use Coinflip\Randomness;
use PHPUnit\Framework\TestCase;

use function Coinflip\is_lucky;

class RandomnessTest extends TestCase
{
    /**
     * Test that is_lucky() returns a boolean value.
     */
    public function testIsLuckyReturnsBoolean(): void
    {
        $result = Randomness::is_lucky();
        $this->assertIsBool($result);
    }

    /**
     * Test that the function form of is_lucky() works correctly.
     */
    public function testFunctionFormReturnsBoolean(): void
    {
        $result = is_lucky();
        $this->assertIsBool($result);
    }

    /**
     * Test that is_lucky() produces approximately 50% true and 50% false results.
     * This test verifies statistical properties over many trials.
     */
    public function testIsLuckyStatisticalDistribution(): void
    {
        $iterations = 10000;
        $trueCount = 0;

        for ($i = 0; $i < $iterations; $i++) {
            if (Randomness::is_lucky()) {
                $trueCount++;
            }
        }

        $trueRatio = $trueCount / $iterations;

        // With 10000 trials, the ratio should be close to 0.5
        // Allow for statistical variance (using 3 standard deviations)
        // For Bernoulli with p=0.5, std dev = sqrt(n*p*(1-p)) = sqrt(2500) = 50
        // 3 std devs = 150, so ratio should be between 0.485 and 0.515
        $this->assertGreaterThan(0.485, $trueRatio);
        $this->assertLessThan(0.515, $trueRatio);
    }

    /**
     * Test that seeding produces deterministic results.
     */
    public function testSeedingProducesDeterministicResults(): void
    {
        // First run with seed
        Randomness::seed(12345);
        $results1 = [];
        for ($i = 0; $i < 100; $i++) {
            $results1[] = Randomness::is_lucky();
        }

        // Second run with same seed should produce identical results
        Randomness::seed(12345);
        $results2 = [];
        for ($i = 0; $i < 100; $i++) {
            $results2[] = Randomness::is_lucky();
        }

        $this->assertEquals($results1, $results2);
    }

    /**
     * Test that different seeds produce different sequences.
     */
    public function testDifferentSeedsProduceDifferentResults(): void
    {
        // First run with seed 1
        Randomness::seed(1);
        $results1 = [];
        for ($i = 0; $i < 100; $i++) {
            $results1[] = Randomness::is_lucky();
        }

        // Second run with seed 2
        Randomness::seed(2);
        $results2 = [];
        for ($i = 0; $i < 100; $i++) {
            $results2[] = Randomness::is_lucky();
        }

        // The sequences should be different (highly unlikely to be the same)
        $this->assertNotEquals($results1, $results2);
    }

    /**
     * Test that results are independent (no correlation).
     * This is a simple test that checks consecutive calls don't always return the same value.
     */
    public function testResultsAreIndependent(): void
    {
        $sameAsLastCount = 0;
        $last = Randomness::is_lucky();

        for ($i = 0; $i < 1000; $i++) {
            $current = Randomness::is_lucky();
            if ($current === $last) {
                $sameAsLastCount++;
            }
            $last = $current;
        }

        // With independent random results, about 50% should be the same as the previous
        // Allow wide margin for statistical variance
        $ratio = $sameAsLastCount / 1000;
        $this->assertGreaterThan(0.4, $ratio);
        $this->assertLessThan(0.6, $ratio);
    }
}
