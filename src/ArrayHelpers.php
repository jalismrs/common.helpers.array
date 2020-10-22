<?php
declare(strict_types = 1);

namespace Jalismrs\HelpersArrayBundle;

use function array_map;

/**
 * Class ArrayHelpers
 *
 * @package Jalismrs\HelpersArrayBundle
 */
final class ArrayHelpers
{
    /**
     * pluck
     *
     * @static
     *
     * @param string $property
     * @param array  $items
     *
     * @return array
     */
    public static function pluck(
        string $property,
        array $items
    ) : array {
        // get values of property in items
        return array_map(
            static function(
                array $item
            ) use
            (
                $property
            ) {
                return $item[$property] ?? null;
            },
            $items
        );
    }
}
