# copmmon.helpers.array

Adds array helper methods

## Test

`phpunit` or `vendor/bin/phpunit`

coverage reports will be available in `var/coverage`

## Use

### pluckOne
```php
use Jalismrs\Common\Helpers\ArrayHelpers;

$input = [
    'key' => [
        'property1' => 'property1value1',
        'property2' => 'property2value1',
    ],
    [
        'property1' => 'property1value2',
        'property2' => 'property2value2',
    ],
];

$output = ArrayHelpers::pluckOne(
    $input,
    'property1',
);

$output = [
    'key' => 'property1value1',
    'property1value2',
];
```

### pluckMany
```php
use Jalismrs\Common\Helpers\ArrayHelpers;

$input = [
    'key' => [
        'property1' => 'property1value1',
        'property2' => 'property2value1',
        'property3' => 'property3value1',
    ],
    [
        'property1' => 'property1value2',
        'property2' => 'property2value2',
        'property3' => 'property3value2',
    ],
];

$output = ArrayHelpers::pluckMany(
    $input,
    [
        'property1',
        'property3',
    ],
);

$output = [
    'key' => [
        'property1' => 'property1value1',
        'property3' => 'property3value1',
    ],
    [
        'property1' => 'property1value2',
        'property3' => 'property3value2',
    ],
];
```

### split
```php
use Jalismrs\Common\Helpers\ArrayHelpers;

$input = [
    42,
    -69,
];

$output = ArrayHelpers::split(
    $input,
    static function(
        int $value
    ): bool {
        return $value < 10;
    },
);

$output = [
    ArrayHelpers::SPLIT_MATCHES     => [
        -69
    ],
    ArrayHelpers::SPLIT_NOT_MATCHES => [
        42,
    ],
];
```
