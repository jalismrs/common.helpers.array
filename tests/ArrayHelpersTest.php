<?php
declare(strict_types = 1);

namespace Tests;

use InvalidArgumentException;
use Jalismrs\Helpers\Arrays\ArrayHelpers;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrayHelpersTest
 *
 * @package Tests
 *
 * @covers  \Jalismrs\Helpers\Arrays\ArrayHelpers
 */
final class ArrayHelpersTest extends
    TestCase
{
    /**
     * testPluckMany
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPluckOne() : void
    {
        // arrange
        $input    = [
            ArrayHelpersProvider::KEY1 => [
                ArrayHelpersProvider::PROPERTY1 => ArrayHelpersProvider::VALUE1,
            ],
        ];
        
        // act
        $output = ArrayHelpers::pluckOne(
            $input,
            ArrayHelpersProvider::PROPERTY1,
        );
        
        // assert
        self::assertCount(
            1,
            $output,
        );
        self::assertArrayHasKey(
            ArrayHelpersProvider::KEY1,
            $output,
        );
        self::assertSame(
            ArrayHelpersProvider::VALUE1,
            $output[ArrayHelpersProvider::KEY1],
        );
    }
    
    /**
     * testPluckMany
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPluckMany() : void
    {
        // arrange
        $input    = [
            ArrayHelpersProvider::KEY2 => [
                ArrayHelpersProvider::PROPERTY2 => ArrayHelpersProvider::VALUE2,
            ],
        ];
        
        // act
        $output = ArrayHelpers::pluckMany(
            $input,
            [
                ArrayHelpersProvider::PROPERTY2,
            ],
        );
        
        // assert
        self::assertCount(
            1,
            $output,
        );
        self::assertArrayHasKey(
            ArrayHelpersProvider::KEY2,
            $output,
        );
        self::assertIsArray(
            $output[ArrayHelpersProvider::KEY2],
        );
        self::assertCount(
            1,
            $output[ArrayHelpersProvider::KEY2],
        );
        self::assertArrayHasKey(
            ArrayHelpersProvider::PROPERTY2,
            $output[ArrayHelpersProvider::KEY2],
        );
        self::assertSame(
            ArrayHelpersProvider::VALUE2,
            $output[ArrayHelpersProvider::KEY2][ArrayHelpersProvider::PROPERTY2],
        );
    }
    
    /**
     * testPluckManyThrowsInvalidArgumentException
     *
     * @param array  $providedInput
     * @param string $providedOutput
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     *
     * @dataProvider \Tests\ArrayHelpersProvider::providePluckThrowsInvalidArgumentException
     */
    public function testPluckManyThrowsInvalidArgumentException(
        array $providedInput,
        string $providedOutput
    ) : void {
        // expect
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($providedOutput);
        
        // act
        ArrayHelpers::pluckMany(
            $providedInput,
            [
                'property',
            ],
        );
    }
    
    /**
     * testSplit
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSplit() : void
    {
        // arrange
        $input    = [
            -69,
            42,
        ];
        $splitter = static function(
            int $item
        ) : bool {
            return $item < 10;
        };
        
        // act
        $output = ArrayHelpers::split(
            $input,
            $splitter,
        );
        
        // assert
        self::assertCount(
            2,
            $output
        );
        self::assertArrayHasKey(
            ArrayHelpers::SPLIT_MATCHES,
            $output
        );
        self::assertArrayHasKey(
            ArrayHelpers::SPLIT_NOT_MATCHES,
            $output
        );
        
        self::assertCount(
            1,
            $output[ArrayHelpers::SPLIT_MATCHES]
        );
        self::assertContains(
            -69,
            $output[ArrayHelpers::SPLIT_MATCHES]
        );
        
        self::assertCount(
            1,
            $output[ArrayHelpers::SPLIT_NOT_MATCHES]
        );
        self::assertContains(
            42,
            $output[ArrayHelpers::SPLIT_NOT_MATCHES]
        );
    }
    
    /**
     * testSplitThrowsInvalidArgumentException
     *
     * @param callable $providedInput
     * @param string   $providedOutput
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     *
     * @dataProvider \Tests\ArrayHelpersProvider::provideSplitThrowsInvalidArgumentException
     */
    public function testSplitThrowsInvalidArgumentException(
        callable $providedInput,
        string $providedOutput
    ) : void {
        // expect
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($providedOutput);
        
        // act
        ArrayHelpers::split(
            [],
            $providedInput,
        );
    }
    
    /**
     * testReflectionOf
     *
     * @param $providedInput
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     *
     * @dataProvider \Tests\ArrayHelpersProvider::provideReflectionOf
     */
    public function testReflectionOf(
        $providedInput
    ) : void {
        // expect
        $this->expectException(InvalidArgumentException::class);
        
        // act
        ArrayHelpers::split(
            [],
            $providedInput,
        );
    }
}
