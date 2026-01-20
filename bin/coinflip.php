#!/usr/bin/env php
<?php

declare(strict_types=1);

// Load Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use function Coinflip\flip;

/**
 * Display usage information.
 */
function showUsage(): void
{
    echo "Usage: php bin/coinflip.php <times>\n";
    echo "       php bin/coinflip.php --help\n";
    echo "\n";
    echo "Arguments:\n";
    echo "  <times>    Number of coin flips to perform (must be a non-negative integer)\n";
    echo "\n";
    echo "Options:\n";
    echo "  --help     Show this help message\n";
    echo "\n";
    echo "Examples:\n";
    echo "  php bin/coinflip.php 10     # Flip a coin 10 times\n";
    echo "  php bin/coinflip.php 0      # Return empty result (no flips)\n";
}

/**
 * Format boolean value as human-readable string.
 */
function formatResult(bool $value): string
{
    return $value ? 'true' : 'false';
}

/**
 * Display results in a user-friendly format.
 */
function displayResults(array $results): void
{
    if (empty($results)) {
        echo "No flips performed.\n";
        return;
    }

    echo "Results:\n";
    foreach ($results as $index => $result) {
        $resultStr = formatResult($result);
        echo "  " . ($index + 1) . ". {$resultStr}\n";
    }

    // Display summary statistics
    $trueCount = count(array_filter($results, fn($r) => $r === true));
    $falseCount = count($results) - $trueCount;

    echo "\nSummary:\n";
    echo "  Total flips: " . count($results) . "\n";
    echo "  True: {$trueCount}\n";
    echo "  False: {$falseCount}\n";
}

// Main CLI logic
try {
    // Check if help is requested
    if (isset($argv[1]) && ($argv[1] === '--help' || $argv[1] === '-h')) {
        showUsage();
        exit(0);
    }

    // Check if argument is provided
    if (!isset($argv[1])) {
        echo "Error: Missing required argument <times>\n\n";
        showUsage();
        exit(1);
    }

    $input = $argv[1];

    // Validate that input is a valid integer
    if (!is_numeric($input)) {
        echo "Error: Argument must be a valid integer, got: '{$input}'\n";
        exit(1);
    }

    // Check if input has decimal point (not a whole number)
    if (str_contains($input, '.')) {
        echo "Error: Argument must be a whole number (integer), got: '{$input}'\n";
        exit(1);
    }

    // Convert to integer
    $times = (int) $input;

    // Additional validation: check if the string representation matches the integer
    // This catches cases like "123abc" which (int) would convert to 123
    if ((string) $times !== $input) {
        echo "Error: Argument must be a valid integer, got: '{$input}'\n";
        exit(1);
    }

    // Call the flip function (this will throw InvalidArgumentException if $times < 0)
    $results = flip($times);

    // Display the results
    displayResults($results);

    exit(0);

} catch (\InvalidArgumentException $e) {
    echo "Error: {$e->getMessage()}\n";
    exit(1);
} catch (\Throwable $e) {
    echo "Unexpected error: {$e->getMessage()}\n";
    exit(1);
}
