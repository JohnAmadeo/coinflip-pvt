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
