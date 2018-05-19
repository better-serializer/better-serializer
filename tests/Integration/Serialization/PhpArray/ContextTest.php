<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace Integration\Serialization\PhpArray;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\DataBind\Writer\SerializationContext;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Radio;
use Integration\AbstractIntegrationTest;

/**
 *
 */
final class ContextTest extends AbstractIntegrationTest
{

    /**
     * @dataProvider getTestTuples
     * @group integration
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param mixed $data
     * @param mixed $expectedData
     * @param string[] $groups
     * @throws \LogicException
     * @throws \ReflectionException
     * @throws \RuntimeException
     */
    public function testSerialization($data, $expectedData, array $groups): void
    {
        $serializer = $this->getSerializer();

        $context = new SerializationContext($groups);
        $json = $serializer->serialize($data, SerializationType::PHP_ARRAY(), $context);
        self::assertSame($expectedData, $json);
    }

    /**
     * @dataProvider getTestTuples
     * @group integration
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param mixed $data
     * @param mixed $expectedData
     * @param string[] $groups
     * @throws \LogicException
     * @throws \ReflectionException
     * @throws \RuntimeException
     */
    public function testSerializationCached($data, $expectedData, array $groups): void
    {
        $serializer = $this->getCachedSerializer();

        $context = new SerializationContext($groups);
        $json = $serializer->serialize($data, SerializationType::PHP_ARRAY(), $context);
        self::assertSame($expectedData, $json);
    }

    /**
     * @return array
     */
    public function getTestTuples(): array
    {
        return [
            $this->getNestedObjectTripleGroup1(),
            $this->getNestedObjectTripleGroup2(),
            $this->getNestedObjectTripleGroup1Group2(),
        ];
    }

    /**
     * @return array
     */
    private function getNestedObjectTripleGroup1(): array
    {
        $radio = new Radio('test station');
        $car = new Car('Honda', 'white', $radio);
        $data = [
            'title' => 'Honda',
            'color' => 'white',
            'radio' => [
                'brand' => 'test station',
            ],
        ];

        return [$car, $data, ['group1']];
    }

    /**
     * @return array
     */
    private function getNestedObjectTripleGroup2(): array
    {
        $radio = new Radio('test station');
        $car = new Car('Honda', 'white', $radio);
        $data = [
            'title' => 'Honda',
            'color' => 'white',
            'doors' => [],
        ];

        return [$car, $data, ['group2']];
    }

    /**
     * @return array
     */
    private function getNestedObjectTripleGroup1Group2(): array
    {
        $radio = new Radio('test station');
        $car = new Car('Honda', 'white', $radio);
        $data = [
            'title' => 'Honda',
            'color' => 'white',
            'radio' => [
                'brand' => 'test station',
            ],
            'doors' => [],
        ];

        return [$car, $data, ['group1', 'group2']];
    }
}
