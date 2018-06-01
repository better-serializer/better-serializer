<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace Integration\Serialization\PhpArray;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\Dto\Aliases;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Car2;
use BetterSerializer\Dto\Category;
use BetterSerializer\Dto\Door;
use BetterSerializer\Dto\Nested\CarFactory;
use BetterSerializer\Dto\Radio;
use BetterSerializer\Dto\SpecialCar;
use Integration\AbstractIntegrationTest;
use DateTime;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\ExpectationFailedException;
use Pimple\Exception\UnknownIdentifierException;
use RuntimeException;
use SebastianBergmann\RecursionContext\InvalidArgumentException as RecursionInvalidArgumentException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class BasicTest extends AbstractIntegrationTest
{

    /**
     * @dataProvider getTestTuples
     * @group integration
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param mixed $data
     * @param mixed $expectedData
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @throws UnknownIdentifierException
     * @throws RuntimeException
     * @throws RecursionInvalidArgumentException
     */
    public function testSerialization($data, $expectedData): void
    {
        $serializer = $this->getSerializer();

        $json = $serializer->serialize($data, SerializationType::PHP_ARRAY());
        self::assertSame($expectedData, $json);
    }

    /**
     * @dataProvider getTestTuples
     * @group integration
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param mixed $data
     * @param mixed $expectedData
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RecursionInvalidArgumentException
     * @throws RuntimeException
     * @throws UnknownIdentifierException
     */
    public function testSerializationCached($data, $expectedData): void
    {
        $serializer = $this->getCachedSerializer();

        $json = $serializer->serialize($data, SerializationType::PHP_ARRAY());
        self::assertSame($expectedData, $json);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getTestTuples(): array
    {
        return [
            $this->getNestedObjectTuple(),
            $this->getNestedObjectTupleAndArray(),
            $this->getObjectsInArrayTuple(),
            $this->getObjectsInArrayTupleWithInnerArray(),
            $this->getStringsInArray(),
            $this->getInheritedObjectTuple(),
            $this->getOverridenNameTuple(),
            $this->getNamespaceFeatureTupleWithDateTimes(),
            $this->getRecursiveDataTuple(),
            $this->getPrimitiveDataTuple(),
            $this->getAliasesTuple(),
        ];
    }

    /**
     * @return array
     */
    private function getNestedObjectTuple(): array
    {
        $radio = new Radio('test station');
        $car = new Car('Honda', 'white', $radio);
        $data = [
            'title' => 'Honda',
            'color' => 'white',
            'radio' => [
                'brand' => 'test station'
            ],
            'doors' => []
        ];

        return [$car, $data];
    }

    /**
     * @return array
     */
    private function getNestedObjectTupleAndArray(): array
    {
        $radio = new Radio('test station');
        $door = new Door();
        $doors = [$door, $door];
        $car = new Car('Honda', 'white', $radio, $doors);
        $data = [
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

        return [$car, $data];
    }

    /**
     * @return array
     */
    private function getObjectsInArrayTuple(): array
    {
        $radio = new Radio('test station');
        $car = new Car('Honda', 'white', $radio);
        $cars = [];
        $data = [];

        for ($i = 0; $i < 2; $i++) {
            $cars[] = $car;
            $data[] = [
                'title' => 'Honda',
                'color' => 'white',
                'radio' => [
                    'brand' => 'test station'
                ],
                'doors' => []
            ];
        }

        return [$cars, $data];
    }

    /**
     * @return array
     */
    private function getObjectsInArrayTupleWithInnerArray(): array
    {
        $radio = new Radio('test station');
        $door = new Door();
        $doors = [$door, $door];
        $car = new Car('Honda', 'white', $radio, $doors);
        $cars = [];
        $data = [];

        for ($i = 0; $i < 2; $i++) {
            $cars[] = $car;
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

        return [$cars, $data];
    }

    /**
     * @return array
     */
    private function getStringsInArray(): array
    {
        $string = 'test';
        $strings = [];
        $data = [];

        for ($i = 0; $i < 2; $i++) {
            $strings[] = $string;
            $data[] = $string;
        }

        return [$strings, $data];
    }

    /**
     * @return array
     */
    private function getInheritedObjectTuple(): array
    {
        $radio = new Radio('test station');
        $car = new SpecialCar('Honda', 'white', $radio, 'special');
        $data = [
            'title' => 'Honda',
            'color' => 'white',
            'radio' => [
                'brand' => 'test station'
            ],
            'doors' => [],
            'special' => 'special'
        ];

        return [$car, $data];
    }

    /**
     * @return array
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function getOverridenNameTuple(): array
    {
        $car2 = new Car2(
            'testTitle',
            DateTime::createFromFormat('Y-m-d H:i:s', '2010-09-01 08:07:06'),
            DateTime::createFromFormat(DateTime::ATOM, '2017-08-19T17:31:09+00:00'),
            DateTimeImmutable::createFromFormat(DateTime::ATOM, '2017-08-19T17:31:09+00:00')
        );
        $data = [
            'serializedTitle' => 'testTitle',
            'manufactured' => '2010-09-01 08:07:06',
            'selled' => '2017-08-19T17:31:09+00:00',
            'serviced' => '2017-08-19T17:31:09+00:00',
            'dismantled' => null,
        ];

        return [$car2, $data];
    }

    /**
     * @return array
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function getNamespaceFeatureTupleWithDateTimes(): array
    {
        $radio = new Radio('test station');
        $car = new SpecialCar('Honda', 'white', $radio, 'special');

        $car2 = new Car2(
            'testTitle',
            DateTime::createFromFormat('Y-m-d H:i:s', '2010-09-01 08:07:06'),
            DateTime::createFromFormat(DateTime::ATOM, '2017-08-19T17:31:09+00:00'),
            DateTimeImmutable::createFromFormat(DateTime::ATOM, '2017-08-19T17:31:09+00:00')
        );

        $factory = new CarFactory([$car], [$car2]);
        $data = [
            'cars' => [
                [
                    'title' => 'Honda',
                    'color' => 'white',
                    'radio' => [
                        'brand' => 'test station'
                    ],
                    'doors' => [],
                    'special' => 'special'
                ],
            ],
            'cars2' => [
                [
                    'serializedTitle' => 'testTitle',
                    'manufactured' => '2010-09-01 08:07:06',
                    'selled' => '2017-08-19T17:31:09+00:00',
                    'serviced' => '2017-08-19T17:31:09+00:00',
                    'dismantled' => null,
                ],
            ],
        ];

        return [$factory, $data];
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getRecursiveDataTuple(): array
    {
        $parent = new Category(1);
        $category = new Category(2, $parent);
        $dateTime = (new DateTimeImmutable())->format(DateTime::ATOM);

        $data = [
            'id' => 2,
            'parent' => [
                'id' => 1,
                'parent' => null,
                'children' => [],
                'createdAt' =>  $dateTime,
                'updatedAt' => null
            ],
            'children' => [],
            'createdAt' => $dateTime,
            'updatedAt' => null,
        ];

        return [$category, $data];
    }

    /**
     * @return array
     */
    private function getPrimitiveDataTuple(): array
    {
        return [6, 6];
    }

    /**
     * @return array
     */
    private function getAliasesTuple(): array
    {
        $aliases = new Aliases(1, 2);
        $data = [
            'integer1' => 1,
            'integer2' => 2,
        ];

        return [$aliases, $data];
    }
}
