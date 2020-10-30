<?php
declare(strict_types = 1);

namespace Jalismrs\Helpers\Arrays;

use Closure;
use InvalidArgumentException;
use ReflectionException;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionNamedType;
use function array_key_exists;
use function count;
use function explode;
use function is_array;
use function is_string;

/**
 * Class ArrayHelpers
 *
 * @package Jalismrs\Helpers\Arrays
 */
final class ArrayHelpers
{
    public const SPLIT_MATCHES     = 'matches';
    public const SPLIT_NOT_MATCHES = '!matches';
    
    /**
     * pluckOne
     *
     * @static
     *
     * @param array $items
     * @param       $property
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public static function pluckOne(
        array $items,
        $property
    ) : array {
        $output = [];
        
        $items = self::pluckMany(
            $items,
            [
                $property,
            ]
        );
        foreach ($items as $index => $item) {
            $output[$index] = $item[$property];
        }
        
        return $output;
    }
    
    
    /**
     * pluckMany
     *
     * @static
     *
     * @param array $items
     * @param array $properties
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public static function pluckMany(
        array $items,
        array $properties
    ) : array {
        $output = [];
        
        foreach ($items as $index => $item) {
            if (!is_array(
                $item,
            )) {
                throw new InvalidArgumentException(
                    "Item#{$index} is not an array"
                );
            }
            
            $output[$index] = [];
            
            foreach ($properties as $property) {
                if (!array_key_exists(
                    $property,
                    $item,
                )) {
                    throw new InvalidArgumentException(
                        "Item#{$index} lacks property '{$property}'"
                    );
                }
                
                $output[$index][$property] = $item[$property];
            }
        }
        
        return $output;
    }
    
    /**
     * split
     *
     * @static
     *
     * @param array    $items
     * @param callable $splitter
     *
     * @return array[]
     *
     * @throws \InvalidArgumentException
     */
    public static function split(
        array $items,
        callable $splitter
    ) : array {
        try {
            $reflection = self::reflectionOf($splitter);
            // @codeCoverageIgnoreStart
        } catch (ReflectionException $reflectionException) {
            throw new InvalidArgumentException(
                'Unable to process splitter',
                0,
                $reflectionException
            );
        }
        // @codeCoverageIgnoreEnd
        
        $nbParameters = $reflection->getNumberOfParameters();
        if ($nbParameters !== 1) {
            throw new InvalidArgumentException(
                "splitter has {$nbParameters} parameters whereas only 1 parameter is expected",
            );
        }
        $reflectionType = $reflection->getReturnType();
        if ($reflectionType === null) {
            throw new InvalidArgumentException(
                'splitter lacks a return type',
            );
        }
        if (!$reflectionType instanceof ReflectionNamedType) {
            // @codeCoverageIgnoreStart
            throw new InvalidArgumentException(
                'Unable to process return type',
            );
            // @codeCoverageIgnoreEnd
        }
        if ($reflectionType->allowsNull()) {
            throw new InvalidArgumentException(
                'splitter allows a nullable return type',
            );
        }
        $returnType = $reflectionType->getName();
        if ($returnType !== 'bool') {
            throw new InvalidArgumentException(
                "splitter returns a '{$returnType}' whereas 'bool' is expected",
            );
        }
        
        $output = [
            self::SPLIT_MATCHES     => [],
            self::SPLIT_NOT_MATCHES => [],
        ];
        
        foreach ($items as $item) {
            $key = $splitter($item)
                ? self::SPLIT_MATCHES
                : self::SPLIT_NOT_MATCHES;
            
            $output[$key][] = $item;
        }
        
        return $output;
    }
    
    /**
     * reflectionOf
     *
     * @static
     *
     * @param callable $callable
     *
     * @return \ReflectionFunctionAbstract
     *
     * @throws \ReflectionException
     */
    private static function reflectionOf(
        callable $callable
    ) : ReflectionFunctionAbstract {
        $reflection = null;
        
        if ($callable instanceof Closure) {
            $reflection = new ReflectionFunction($callable);
        }
        
        if (
            $reflection === null
            &&
            is_string($callable)
        ) {
            $callableParts = explode(
                '::',
                $callable
            );
            
            $reflection = count($callableParts) > 1
                ? new ReflectionMethod(
                    $callableParts[0],
                    $callableParts[1]
                )
                : new ReflectionFunction($callable);
        }
        
        if ($reflection === null) {
            $reflection = is_array($callable)
                ? new ReflectionMethod(
                    $callable[0],
                    $callable[1]
                )
                : new ReflectionMethod(
                    $callable,
                    '__invoke'
                );
        }
        
        return $reflection;
    }
}
