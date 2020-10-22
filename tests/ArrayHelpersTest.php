<?php
declare(strict_types = 1);

namespace Tests;

use Jalismrs\HelpersArrayBundle\ArrayHelpers;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrayHelpersTest
 *
 * @package Tests
 *
 * @covers  \Jalismrs\HelpersArrayBundle\ArrayHelpers
 */
final class ArrayHelpersTest extends
    TestCase
{
    /**
     * testPluck
     *
     * @param array $providedInput
     * @param array $providedOutput
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @dataProvider \Tests\ArrayHelpersProvider::providePluck
     */
    public function testPluck(
        array $providedInput,
        array $providedOutput
    ) : void {
        // act
        $output = ArrayHelpers::pluck(
            $providedInput['property'],
            $providedInput['items']
        );

        // assert
        self::assertSame(
            $providedOutput,
            $output
        );
    }
}
