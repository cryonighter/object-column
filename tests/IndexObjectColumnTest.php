<?php

namespace Cryonighter\ObjectColumn\Test;

use ArrayObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use function Cryonighter\ObjectColumn\object_column;

class IndexObjectColumnTest extends TestCase
{
    /**
     * @dataProvider arrayProvider
     *
     * @param array $data
     */
    public function testArray(array $data): void
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
     * @dataProvider arrayAccessProvider
     *
     * @param array $data
     */
    public function testArrayAccess(array $data): void
    {
        $result = object_column($data, null, 'bar.buz');

        $expected = [
            '456' => new ArrayObject([
                'foo' => new ArrayObject(['baz' => '123']),
                'bar' => new ArrayObject(['buz' => '456']),
            ]),
            'asd' => new ArrayObject([
                'foo' => new ArrayObject(['baz' => 'qwe']),
                'bar' => new ArrayObject(['buz' => 'asd']),
            ]),
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider allProvider
     *
     * @param array $data
     */
    public function testWithoutParams(array $data): void
    {
        $result = object_column($data);

        $this->assertEquals($data, $result);
    }

    /**
     * @dataProvider allProvider
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
    public function arrayProvider(): array
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

    /**
     * @return array
     */
    public function arrayAccessProvider(): array
    {
        $arrayAccess = [
            new ArrayObject([
                'foo' => new ArrayObject(['baz' => '123']),
                'bar' => new ArrayObject(['buz' => '456']),
            ]),
            new ArrayObject([
                'foo' => new ArrayObject(['baz' => 'qwe']),
                'bar' => new ArrayObject(['buz' => 'asd']),
            ]),
        ];

        return [[$arrayAccess]];
    }

    /**
     * @return array
     */
    public function allProvider(): array
    {
        return [
            ...$this->arrayProvider(),
            ...$this->arrayAccessProvider(),
        ];
    }
}
