<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */
namespace ZeroConfig\Cloner\Tests;

use PHPUnit\Framework\TestCase;
use stdClass;
use ZeroConfig\Cloner\Cloner;

/**
 * @coversDefaultClass \ZeroConfig\Cloner\Cloner
 */
class ClonerTest extends TestCase
{
    /**
     * @dataProvider cloneProvider
     *
     * @param object   $subject
     * @param callable $modifier
     * @param string   $expected
     *
     * @return void
     * @covers ::__invoke
     * @covers ::cloneObject
     * @covers ::cloneArray
     */
    public function testInvoke(
        object $subject,
        callable $modifier,
        string $expected
    ): void {
        $cloner = new Cloner();
        $clone  = $cloner($subject);

        $this->assertInternalType('object', $clone);

        $modified = $modifier($clone);

        $this->assertEquals(
            $expected,
            $this->createSignature($subject, $modified)
        );
    }

    /**
     * Create a signature for the given subject and clone.
     *
     * @param object $subject
     * @param object $clone
     *
     * @return string
     */
    private function createSignature(object $subject, object $clone): string
    {
        return json_encode(
            [
                'subject' => $subject,
                'clone' => $clone
            ]
        );
    }

    /**
     * @return object[][]|callable[][]|string[][]
     */
    public function cloneProvider(): array
    {
        return [
            $this->createFlatObjectArguments(),
            $this->createNestedObjectArguments(),
            $this->createSiblingObjectArguments(),
            $this->createStaticPropertyObjectArguments(),
            $this->createNestedArrayObjectArguments()
        ];
    }

    /**
     * @return object[]|callable[]|string[]
     */
    private function createFlatObjectArguments(): array
    {
        return [
            $this->createObject(
                [
                    'foo' => 'bar',
                    'baz' => 'qux'
                ]
            ),
            function (object $clone) : object {
                foreach ((array)$clone as $property => $value) {
                    $clone->{$property} = strtoupper($value);
                }

                return $clone;
            },
            $this->createSignature(
                $this->createObject(
                    [
                        'foo' => 'bar',
                        'baz' => 'qux'
                    ]
                ),
                $this->createObject(
                    [
                        'foo' => 'BAR',
                        'baz' => 'QUX'
                    ]
                )
            )
        ];
    }

    /**
     * @return object[]|callable[]|string[]
     */
    private function createNestedObjectArguments(): array
    {
        $object = $this->createObject(
            [
                'foo' => [
                    'bar' => 'baz'
                ]
            ]
        );

        return [
            $object,
            function (object $clone) : object {
                return $clone;
            },
            $this->createSignature($object, $object)
        ];
    }

    /**
     * @return object[]|callable[]|string[]
     */
    private function createSiblingObjectArguments(): array
    {
        $winter                    = new stdClass();
        $winter->amsterdam         = new stdClass();
        $winter->holland           = $winter->amsterdam;
        $winter->amsterdam->offset = '+1';

        return [
            $winter,
            function (object $summer) : object {
                if (isset($summer->holland->offset)) {
                    $summer->holland->offset = '+2';
                }

                return $summer;
            },
            $this->createSignature(
                $winter,
                $this->createObject(
                    [
                        'amsterdam' => [
                            'offset' => '+2'
                        ],
                        'holland' => [
                            'offset' => '+2'
                        ]
                    ]
                )
            )
        ];
    }

    /**
     * @return object[]|callable[]|string[]
     */
    private function createStaticPropertyObjectArguments(): array
    {
        $object = new class {
            /**
             * @var string[]
             */
            public static $data = ['foo', 'bar', 'baz'];
        };

        return [
            $object,
            function (object $clone) : object {
                return $clone;
            },
            $this->createSignature($object, $object)
        ];
    }

    /**
     * @return object[]|callable[]|string[]
     */
    private function createNestedArrayObjectArguments(): array
    {
        $object = $this->createObject(
            [
                'foo' => [
                    ['bar' => 'baz'],
                    [
                        'qux' => [
                            ['q'],
                            ['u'],
                            ['x']
                        ]
                    ]
                ]
            ]
        );

        return [
            $object,
            function (object $clone) : object {
                if (property_exists($clone, 'foo')) {
                    foreach ($clone->foo ?? [] as $index => $object) {
                        $clone->foo[$index] = array_map(
                            function ($value) {
                                return is_string($value)
                                    ? strtoupper($value)
                                    : current((array)$value);
                            },
                            (array)$object
                        );
                    }
                }

                return $clone;
            },
            $this->createSignature(
                $object,
                $this->createObject(
                    [
                        'foo' => [
                            ['bar' => 'BAZ'],
                            ['qux' => ['q']]
                        ]
                    ]
                )
            )
        ];
    }

    /**
     * Create an object for the given data.
     *
     * @param array $data
     *
     * @return object
     */
    private function createObject(array $data): object
    {
        return json_decode(
            json_encode($data)
        );
    }
}
