<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Dto\Nested;

use BetterSerializer\DataBind\MetaData\Annotations as Serializer;
use BetterSerializer\Dto\Car2;
use JMS\Serializer\Annotation as JmsSerializer;
use BetterSerializer\Dto\SpecialCar;

/**
 *
 */
final class CarFactory
{

    /**
     * @var SpecialCar[]
     * @JmsSerializer\Type("array<BetterSerializer\Dto\SpecialCar>")
     */
    private $cars;

    /**
     * @var array
     * @Serializer\Property(type="array<Car2>")
     * @JmsSerializer\Type("array<BetterSerializer\Dto\Car2>")
     */
    private $cars2;

    /**
     * CarFactory constructor.
     * @param SpecialCar[] $cars
     * @param Car2[] $cars2
     */
    public function __construct(array $cars, array $cars2)
    {
        $this->cars = $cars;
        $this->cars2 = $cars2;
    }

    /**
     * @return SpecialCar[]
     */
    public function getCars(): array
    {
        return $this->cars;
    }

    /**
     * @return array
     */
    public function getCars2(): array
    {
        return $this->cars2;
    }
}
