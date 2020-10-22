<?php
declare(strict_types = 1);

namespace Tests;

/**
 * Class ArrayHelpersProvider
 *
 * @package Tests
 */
final class ArrayHelpersProvider
{
    /**
     * provideFormatContractName
     *
     * @return array
     */
    public function providePluck() : array
    {
        return [
            'no items'               => [
                'input'  => [
                    'property' => 'test',
                    'items'    => [],
                ],
                'output' => [],
            ],
            'items missing property' => [
                'input'  => [
                    'property' => 'test',
                    'items'    => [
                        [
                            'test1' => 'value1',
                        ],
                    ],
                ],
                'output' => [
                    null,
                ],
            ],
            '1 item with property'   => [
                'input'  => [
                    'property' => 'test1',
                    'items'    => [
                        [
                            'test1' => 'value1',
                        ],
                        [
                            'test2' => 'value2',
                        ],
                    ],
                ],
                'output' => [
                    'value1',
                    null,
                ],
            ],
            'standard'               => [
                'input'  => [
                    'property' => 'test1',
                    'items'    => [
                        [
                            'test1' => 'value1',
                        ],
                        [
                            'test1' => 'value2',
                        ],
                        [
                            'test1' => 'value3',
                        ],
                        [
                            'test1' => 'value4',
                        ],
                        [
                            'test1' => 'value5',
                        ],
                    ],
                ],
                'output' => [
                    'value1',
                    'value2',
                    'value3',
                    'value4',
                    'value5',
                ],
            ],
        ];
    }
}
