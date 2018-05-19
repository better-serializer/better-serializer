<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace Integration\Deserialization\PhpArray;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Car2;
use BetterSerializer\Dto\Category;
use BetterSerializer\Dto\Nested\CarFactory;
use Integration\AbstractIntegrationTest;
use DateTimeImmutable;
use DateTime;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class BasicTest extends AbstractIntegrationTest
{

    /**
     * @dataProvider getTestData
     * @group integration
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param mixed $expectedData
     * @param string $stringType
     * @throws \LogicException
     * @throws \Pimple\Exception\UnknownIdentifierException
     * @throws \ReflectionException
     * @throws \RuntimeException
     */
    public function testDeserialization($expectedData, string $stringType): void
    {
        $serializer = $this->getSerializer();

        $data = $serializer->deserialize($expectedData, $stringType, SerializationType::PHP_ARRAY());
        $serialized = $serializer->serialize($data, SerializationType::PHP_ARRAY());

        self::assertSame($expectedData, $serialized);
    }

    /**
     * @dataProvider getTestData
     * @group integration
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param mixed $expectedData
     * @param string $stringType
     * @throws \LogicException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Pimple\Exception\UnknownIdentifierException
     * @throws \ReflectionException
     * @throws \RuntimeException
     */
    public function testDeserializationCached($expectedData, string $stringType): void
    {
        $serializer = $this->getCachedSerializer();

        $data = $serializer->deserialize($expectedData, $stringType, SerializationType::PHP_ARRAY());
        $serialized = $serializer->serialize($data, SerializationType::PHP_ARRAY());

        self::assertSame($expectedData, $serialized);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getTestData(): array
    {
        return [
            $this->getNestedObjectTuple(),
            $this->getNestedObjectWithArrayTuple(),
            $this->getObjectsInArrayTuple(),
            $this->getObjectsInArrayTupleWithInnerArray(),
            $this->getStringsInArray(),
            $this->getOverridenNameTuple(),
            $this->getNamespaceFeatureTupleWithDateTimes(),
            $this->getRecursiveDataTuple(),
            $this->getPrimitiveDataTuple(),
        ];
    }

    /**
     * @return array
     */
    private function getNestedObjectTuple(): array
    {
        $data = [
            'title' => 'Honda',
            'color' => 'white',
            'radio' => [
                'brand' => 'test station'
            ],
            'doors' => []
        ];

        return [$data, Car::class];
    }

    /**
     * @return array
     */
    private function getNestedObjectWithArrayTuple(): array
    {
        $data = [
            'title' => 'Honda',
            'color' => 'white',
            'radio' => [
                'brand' => 'test station',
            ],
            'doors' => [
                [
                    'parentalLock' => false
                ],
                [
                    'parentalLock' => false
                ],
            ],
        ];

        return [$data, Car::class];
    }

    /**
     * @return array
     */
    private function getObjectsInArrayTuple(): array
    {
        $data = [];

        for ($i = 0; $i < 2; $i++) {
            $data[] = [
                'title' => 'Honda',
                'color' => 'white',
                'radio' => [
                    'brand' => 'test station'
                ],
                'doors' => []
            ];
        }

        return [$data, 'array<' . Car::class .'>'];
    }

    /**
     * @return array
     */
    private function getObjectsInArrayTupleWithInnerArray(): array
    {
        $data = [];

        for ($i = 0; $i < 2; $i++) {
            $data[] = [
                'title' => 'Honda',
                'color' => 'white',
                'radio' => [
                    'brand' => 'test station'
                ],
                'doors' => [
                    [
                        'parentalLock' => false
                    ],
                    [
                        'parentalLock' => false
                    ]
                ]
            ];
        }

        return [$data, 'array<' . Car::class .'>'];
    }

    /**
     * @return array
     */
    private function getStringsInArray(): array
    {
        $data = [];

        for ($i = 0; $i < 2; $i++) {
            $data[] = 'test';
        }

        return [$data, 'array<string>'];
    }

    /**
     * @return array
     */
    private function getOverridenNameTuple(): array
    {
        $data = [
            'serializedTitle' => 'testTitle',
            'manufactured' => '2010-09-01 08:07:06',
            'selled' => '2017-08-19T17:31:09+00:00',
            'serviced' => '2017-08-19T17:31:09+00:00',
            'dismantled' => null
        ];

        return [$data, Car2::class];
    }

    /**
     * @return array
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function getNamespaceFeatureTupleWithDateTimes(): array
    {
        $data = [
            'cars' => [
                [
                    'title' => 'Honda',
                    'color' => 'white',
                    'radio' => [
                        'brand' => 'test station'
                    ],
                    'doors' => [],
                    'special' => 'special',
                ]
            ],
            'cars2' => [
                [
                    'serializedTitle' => 'testTitle',
                    'manufactured' => '2010-09-01 08:07:06',
                    'selled' => '2017-08-19T17:31:09+00:00',
                    'serviced' => '2017-08-19T17:31:09+00:00',
                    'dismantled' => null,
                ]
            ]
        ];

        return [$data, CarFactory::class];
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getRecursiveDataTuple(): array
    {
        $dateTime = (new DateTimeImmutable())->format(DateTime::ATOM);

        $data = [
            'id' => 2,
            'parent' => [
                'id' => 1,
                'parent' => null,
                'children' => [],
                'createdAt' => $dateTime,
                'updatedAt' => null
            ],
            'children' => [],
            'createdAt' => $dateTime,
            'updatedAt' => null
        ];

        return [$data, Category::class];
    }

    /**
     * @return array
     */
    private function getPrimitiveDataTuple(): array
    {
        return [6, 'int'];
    }
}
