<?php
declare(strict_types = 1);

namespace Jalismrs\Common\Helpers;

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
 * @package Jalismrs\Common\Helpers
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
     * @param array $input
     * @param       $property
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public static function pluckOne(
        array $input,
        $property
    ) : array {
        $output = [];
        
        $input = self::pluckMany(
            $input,
            [
                $property,
            ]
        );
        /**
         * @var array $value
         */
        foreach ($input as $key => $value) {
            $output[$key] = $value[$property];
        }
        
        return $output;
    }
    
    
    /**
     * pluckMany
     *
     * @static
     *
     * @param array $input
     * @param array $properties
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public static function pluckMany(
        array $input,
        array $properties
    ) : array {
        $output = [];
        
        foreach ($input as $key => $value) {
            if (!is_array(
                $value,
            )) {
                throw new InvalidArgumentException(
                    "Item#{$key} is not an array"
                );
            }
            
            $output[$key] = [];
            
            foreach ($properties as $property) {
                if (!array_key_exists(
                    $property,
                    $value,
                )) {
                    throw new InvalidArgumentException(
                        "Item#{$key} lacks property '{$property}'"
                    );
                }
                
                $output[$key][$property] = $value[$property];
            }
        }
        
        return $output;
    }
    
    /**
     * split
     *
     * @static
     *
     * @param array    $input
     * @param callable $splitter
     *
     * @return array[]
     *
     * @throws \InvalidArgumentException
     */
    public static function split(
        array $input,
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
        
        foreach ($input as $value) {
            $key = $splitter($value)
                ? self::SPLIT_MATCHES
                : self::SPLIT_NOT_MATCHES;
            
            $output[$key][] = $value;
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
