<?php

declare(strict_types=1);

namespace Coinflip;

/**
 * Randomness component that provides the is_lucky() function.
 *
 * This class encapsulates the randomness logic for the coinflip library.
 * It implements a Bernoulli random trial with probability 0.5 (unbiased coin).
 * Supports deterministic seeding for testing purposes.
 */
class Randomness
{
    /**
     * Performs a Bernoulli random trial with probability 0.5.
     *
     * @return bool True or false with equal probability (50% each)
     */
    public static function is_lucky(): bool
    {
        // Use mt_rand for compatibility with deterministic seeding via mt_srand
        // mt_rand(0, 1) returns 0 or 1 with equal probability
        return mt_rand(0, 1) === 1;
    }

    /**
     * Seeds the random number generator for deterministic testing.
     *
     * @param int $seed The seed value for the PRNG
     * @return void
     */
    public static function seed(int $seed): void
    {
        mt_srand($seed);
    }
}
