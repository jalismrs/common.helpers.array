<?php
declare(strict_types = 1);

namespace Tests;

use PHPUnit\Framework\Assert;

/**
 * Class ArrayHelpersProvider
 *
 * @package Tests
 */
final class ArrayHelpersProvider
{
    public const KEY1 = 'key';
    public const KEY2 = 42;
    public const PROPERTY1 = 'property';
    public const PROPERTY2 = 69;
    public const VALUE1 = 'value';
    public const VALUE2 = true;
    
    /**
     * providePluckThrowsInvalidArgumentException
     *
     * @return array[]
     */
    public function providePluckThrowsInvalidArgumentException() : array
    {
        return [
            '!array'           => [
                'input'  => [
                    42,
                ],
                'output' => 'Item#0 is not an array',
            ],
            'missing property' => [
                'input'  => [
                    [
                        '!property' => 'value',
                    ],
                ],
                'output' => "Item#0 lacks property 'property'",
            ],
        ];
    }
    
    /**
     * provideSplitThrowsInvalidArgumentException
     *
     * @return array[]
     */
    public function provideSplitThrowsInvalidArgumentException() : array
    {
        return [
            'parameters'           => [
                'input'  => static function() : bool {
                    return true;
                },
                'output' => 'splitter has 0 parameters whereas only 1 parameter is expected',
            ],
            'missing return type'  => [
                'input'  => static function(
                    bool $value
                ) {
                    return $value;
                },
                'output' => 'splitter lacks a return type',
            ],
            'nullable return type' => [
                'input'  => static function(
                    bool $value
                ) : ?bool {
                    return $value;
                },
                'output' => 'splitter allows a nullable return type',
            ],
            'return type'          => [
                'input'  => static function(
                    bool $value
                ) : int {
                    return (int)$value;
                },
                'output' => "splitter returns a 'int' whereas 'bool' is expected",
            ],
        ];
    }
    
    /**
     * provideReflectionOf
     *
     * @return array
     */
    public function provideReflectionOf() : array
    {
        return [
            'closure'       => [
                'input' => static function() : void {
                },
            ],
            'single string' => [
                'input' => 'intval',
            ],
            'double string' => [
                'input' => Assert::class . '::assertTrue',
            ],
            'array'         => [
                'input' => [
                    Assert::class,
                    'assertTrue',
                ],
            ],
            'invokable'     => [
                'input' => new class() {
                    public function __invoke() : void
                    {
                    }
                },
            ],
        ];
    }
}
