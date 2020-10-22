# Symfony Bundle Helpers Array

## Test

`phpunit` OU `vendor/bin/phpunit`

coverage reports will be available in `var/coverage`

## Use

```php
use Jalismrs\HelpersArrayBundle\ArrayHelpers;

class SomeClass {
    public function someCall() {
        $output = ArrayHelpers::pluck($input);
    }
}
```
