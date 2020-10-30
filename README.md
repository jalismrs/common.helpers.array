# Symfony Bundle Helpers Array

Adds several array helper methods

## Test

`phpunit` OU `vendor/bin/phpunit`

coverage reports will be available in `var/coverage`

## Use

### pluck
```php
use Jalismrs\Common\Helpers\ArrayHelpers;

$input = [
    [
        'property' => 'value1',
    ],
    [
        'property' => 'value2',
    ],
];
$output = [
    'value1',
    'value2',
];

$output = ArrayHelpers::pluck(
    $input,
    'property',
);
```

### split
```php
use Jalismrs\Common\Helpers\ArrayHelpers;

$input = [
    5,
    7,
    69,
    420,
];
$output = [
    ArrayHelpers::SPLIT_MATCHES     => [
        5,
        7
    ],
    ArrayHelpers::SPLIT_NOT_MATCHES => [
        69,
        420,
    ],
];

$output = ArrayHelpers::split(
    $input,
    static function(
        int $item
    ): bool {
        return $item < 10;
    },
);
```
