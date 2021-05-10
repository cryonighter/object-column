<?php

namespace Cryonighter\ObjectColumn\Test;

use ArrayObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use function Cryonighter\ObjectColumn\object_column;

class ObjectColumnTest extends TestCase
{
    /**
     * @dataProvider propertyAccessProvider
     *
     * @param array $data
     */
    public function testWithoutKeys(array $data): void
    {
        $result = object_column($data, 'foo');

        $this->assertArrayHasKey(0, $result);
        $this->assertEquals('123', $result[0]);

        $this->assertArrayHasKey(1, $result);
        $this->assertEquals('qwe', $result[1]);
    }

    /**
     * @dataProvider propertyAccessProvider
     *
     * @param array $data
     */
    public function testWithKeys(array $data): void
    {
        $result = object_column($data, 'foo', 'bar');

        $this->assertArrayHasKey('456', $result);
        $this->assertEquals('123', $result['456']);

        $this->assertArrayHasKey('asd', $result);
        $this->assertEquals('qwe', $result['asd']);
    }


    /**
     * @dataProvider propertyAccessProvider
     *
     * @param array $data
     */
    public function testExceptionColumnKeyNotFound(array $data): void
    {
        $this->expectException(RuntimeException::class);

        object_column($data, 'some');
    }

    /**
     * @return array | object[][][]
     */
    public function propertyAccessProvider(): array
    {
        $array = [
            [
                'foo' => '123',
                'bar' => '456',
                'baz' => '789',
            ],
            [
                'foo' => 'qwe',
                'bar' => 'asd',
                'baz' => 'zxc',
            ],
        ];

        $arrayAccess = [
            new ArrayObject([
                'foo' => '123',
                'bar' => '456',
                'baz' => '789',
            ]),
            new ArrayObject([
                'foo' => 'qwe',
                'bar' => 'asd',
                'baz' => 'zxc',
            ]),
        ];

        $properties = [
            new class {
                public $foo = '123';
                public $bar = '456';
                public $baz = '789';
            },
            new class {
                public $foo = 'qwe';
                public $bar = 'asd';
                public $baz = 'zxc';
            },
        ];

        $getters = [
            new class {
                public function getFoo(): string {
                    return '123';
                }
                public function getBar(): string {
                    return '456';
                }
                public function getBaz(): string {
                    return '789';
                }
            },
            new class {
                public function getFoo(): string {
                    return 'qwe';
                }
                public function getBar(): string {
                    return 'asd';
                }
                public function getBaz(): string {
                    return 'zxc';
                }
            },
        ];

        $methods = [
            new class {
                public function foo(): string {
                    return '123';
                }
                public function bar(): string {
                    return '456';
                }
                public function baz(): string {
                    return '789';
                }
            },
            new class {
                public function foo(): string {
                    return 'qwe';
                }
                public function bar(): string {
                    return 'asd';
                }
                public function baz(): string {
                    return 'zxc';
                }
            },
        ];

        return [[$array, $arrayAccess, $properties, $getters, $methods]];
    }
}
