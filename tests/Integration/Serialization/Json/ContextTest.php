<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace Integration\Serialization\Json;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\DataBind\Writer\SerializationContext;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Radio;
use Integration\AbstractIntegrationTest;

/**
 * Class Json
 * @author mfris
 * @package Integration\Serialization
 */
final class ContextTest extends AbstractIntegrationTest
{

    /**
     * @dataProvider getTestTuples
     * @group integration
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param mixed $data
     * @param string $expectedJson
     * @param string[] $groups
     */
    public function testSerialization($data, string $expectedJson, array $groups): void
    {
        $serializer = $this->getSerializer();

        $context = new SerializationContext($groups);
        $json = $serializer->serialize($data, SerializationType::JSON(), $context);
        self::assertSame($expectedJson, $json);
    }

    /**
     * @dataProvider getTestTuples
     * @group integration
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param mixed $data
     * @param string $expectedJson
     * @param string[] $groups
     */
    public function testSerializationCached($data, string $expectedJson, array $groups): void
    {
        $serializer = $this->getCachedSerializer();

        $context = new SerializationContext($groups);
        $json = $serializer->serialize($data, SerializationType::JSON(), $context);
        self::assertSame($expectedJson, $json);
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
        $json = '{"title":"Honda","color":"white","radio":{"brand":"test station"}}';

        return [$car, $json, ['group1']];
    }

    /**
     * @return array
     */
    private function getNestedObjectTripleGroup2(): array
    {
        $radio = new Radio('test station');
        $car = new Car('Honda', 'white', $radio);
        $json = '{"title":"Honda","color":"white","doors":[]}';

        return [$car, $json, ['group2']];
    }

    /**
     * @return array
     */
    private function getNestedObjectTripleGroup1Group2(): array
    {
        $radio = new Radio('test station');
        $car = new Car('Honda', 'white', $radio);
        $json = '{"title":"Honda","color":"white","radio":{"brand":"test station"},"doors":[]}';

        return [$car, $json, ['group1', 'group2']];
    }
}
