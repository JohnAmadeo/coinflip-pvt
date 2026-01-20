# coinflip-pvt

A simple coin flip library with deterministic randomness support for PHP 8.4.

Source: https://github.com/JohnAmadeo/coinflip-pvt.git

## Installation

```bash
composer install
```

## Usage

### Programmatic API

```php
<?php

require_once 'vendor/autoload.php';

use function Coinflip\flip;
use function Coinflip\is_lucky;

// Flip a coin once
$result = is_lucky(); // Returns true or false

// Flip multiple times
$results = flip(10); // Returns array of 10 boolean values
```

### Command Line Interface

The library includes a CLI wrapper for terminal usage:

```bash
# Flip a coin 10 times
php bin/coinflip.php 10

# Show help
php bin/coinflip.php --help

# Zero flips (returns empty result)
php bin/coinflip.php 0
```

The CLI displays results in a user-friendly format with a summary of true/false counts.

## Testing

```bash
composer test
```
