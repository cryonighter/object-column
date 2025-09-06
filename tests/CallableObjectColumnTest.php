<?php

namespace Cryonighter\ObjectColumn\Test;

use PHPUnit\Framework\TestCase;
use function Cryonighter\ObjectColumn\object_column;

class CallableObjectColumnTest extends TestCase
{
    /**
     * @dataProvider objectGettersProvider
     *
     * @param array $data
     */
    public function testObjectGettersWithoutKeys(array $data): void
    {
        $result = object_column($data, fn(object $object): string => $object->getFoo() . '-' . $object->getBar());

        $this->assertArrayHasKey(0, $result);
        $this->assertEquals('123-456', $result[0]);

        $this->assertArrayHasKey(1, $result);
        $this->assertEquals('qwe-asd', $result[1]);
    }

    /**
     * @dataProvider objectGettersProvider
     *
     * @param array $data
     */
    public function testObjectGettersWithKeys(array $data): void
    {
        $result = object_column(
            $data,
            fn(object $object): string => $object->getFoo() . '-' . $object->getBar(),
            fn(object $object): string => $object->getBar() . '-' . $object->getBaz(),
        );

        $this->assertArrayHasKey('456-789', $result);
        $this->assertEquals('123-456', $result['456-789']);

        $this->assertArrayHasKey('asd-zxc', $result);
        $this->assertEquals('qwe-asd', $result['asd-zxc']);
    }

    /**
     * @return array | object[][][]
     */
    public function objectGettersProvider(): array
    {
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

        return [[$getters]];
    }
}
