<?php

namespace Cryonighter\ObjectColumn\Test;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use function Cryonighter\ObjectColumn\object_column;

class IndexObjectColumnTest extends TestCase
{
    /**
     * @dataProvider propertyAccessProvider
     *
     * @param array $data
     */
    public function testWithoutKeys(array $data): void
    {
        $result = object_column($data);

        $this->assertEquals($data, $result);
    }

    /**
     * @dataProvider propertyAccessProvider
     *
     * @param array $data
     */
    public function testWithKeys(array $data): void
    {
        $result = object_column($data, null, 'bar.buz');

        $expected = [
            '456' => [
                'foo' => ['baz' => '123'],
                'bar' => ['buz' => '456'],
            ],
            'asd' => [
                'foo' => ['baz' => 'qwe'],
                'bar' => ['buz' => 'asd'],
            ],
        ];

        $this->assertEquals($expected, $result);
    }


    /**
     * @dataProvider propertyAccessProvider
     *
     * @param array $data
     */
    public function testExceptionColumnKeyNotFound(array $data): void
    {
        $this->expectException(RuntimeException::class);

        object_column($data, null, 'bar.some');
    }

    /**
     * @return array
     */
    public function propertyAccessProvider(): array
    {
        $array = [
            [
                'foo' => ['baz' => '123'],
                'bar' => ['buz' => '456'],
            ],
            [
                'foo' => ['baz' => 'qwe'],
                'bar' => ['buz' => 'asd'],
            ],
        ];

        return [[$array]];
    }
}
