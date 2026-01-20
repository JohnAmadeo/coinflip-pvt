<?php

declare(strict_types=1);

namespace Coinflip;

/**
 * Convenience function that calls Randomness::is_lucky().
 *
 * This function provides a more direct API that matches the original
 * Node.js implementation's is_lucky() import.
 *
 * @return bool True or false with equal probability (50% each)
 */
function is_lucky(): bool
{
    return Randomness::is_lucky();
}

/**
 * Performs multiple coin flips and returns an array of results.
 *
 * This function is the main public API for the coinflip library.
 * It calls is_lucky() $times times and collects the results in an array.
 *
 * @param int $times The number of coin flips to perform (must be >= 0)
 * @return array<int, bool> An indexed array of boolean results
 * @throws \InvalidArgumentException If $times is negative
 */
function flip(int $times): array
{
    if ($times < 0) {
        throw new \InvalidArgumentException(
            "The number of flips must be non-negative, got: {$times}"
        );
    }

    if ($times === 0) {
        return [];
    }

    $results = [];
    for ($i = 0; $i < $times; $i++) {
        $results[] = is_lucky();
    }

    return $results;
}
