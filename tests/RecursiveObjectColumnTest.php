<?php

namespace Cryonighter\ObjectColumn\Test;

use ArrayObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use function Cryonighter\ObjectColumn\object_column;

class RecursiveObjectColumnTest extends TestCase
{
    /**
     * @dataProvider propertyAccessProvider
     *
     * @param array $data
     */
    public function testWithoutKeys(array $data): void
    {
        $result = object_column($data, 'foo.baz');

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
        $result = object_column($data, 'foo.baz', 'bar.buz');

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

        object_column($data, 'foo.some');
    }

    /**
     * @return array | object[][][]
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

        $firstGetters = [
            new class {
                public function getFoo(): object {
                    return new class {
                        public function baz(): string {
                            return '123';
                        }
                    };
                }
                public function getBar(): object {
                    return new class {
                        public function buz(): string {
                            return '456';
                        }
                    };
                }
            },
            new class {
                public function getFoo(): object {
                    return new class {
                        public function baz(): string {
                            return 'qwe';
                        }
                    };
                }
                public function getBar(): object {
                    return new class {
                        public function buz(): string {
                            return 'asd';
                        }
                    };
                }
            },
        ];

        $firstMethods = [
            new class {
                public function foo(): object {
                    return new class {
                        public function getBaz(): string {
                            return '123';
                        }
                    };
                }
                public function bar(): object {
                    return new class {
                        public function getBuz(): string {
                            return '456';
                        }
                    };
                }
            },
            new class {
                public function foo(): object {
                    return new class {
                        public function getBaz(): string {
                            return 'qwe';
                        }
                    };
                }
                public function bar(): object {
                    return new class {
                        public function getBuz(): string {
                            return 'asd';
                        }
                    };
                }
            },
        ];

        return [[$array], [$arrayAccess], [$firstGetters], [$firstMethods]];
    }
}
