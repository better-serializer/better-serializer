<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\Dto;

use BetterSerializer\DataBind\MetaData\Annotations as Serializer;
use JMS\Serializer\Annotation as JmsSerializer;

/**
 * Class Car
 * @author mfris
 * @package BetterSerializer\Dto
 * @Serializer\RootName(value="car")
 */
class Car implements CarInterface
{

    /**
     * @var string
     * @Serializer\Property(type="string")
     * @JmsSerializer\Type("string")
     */
    private $title;

    /**
     * @var string
     * @JmsSerializer\Type("string")
     */
    private $color;

    /**
     * @var Radio|null
     * @JmsSerializer\Type("BetterSerializer\Dto\Radio")
     */
    private $radio;

    /**
     * @var Door[]
     * @Serializer\Property(type="array<Door>")
     * @JmsSerializer\Type("array<BetterSerializer\Dto\Door>")
     */
    private $doors;

    /**
     * Car constructor.
     * @param string $title
     * @param string $color
     * @param Radio|null $music
     * @param Door[] $doors
     * @Serializer\BoundToProperty(propertyName="radio", argumentName="music")
     */
    public function __construct(string $title, string $color, Radio $music = null, array $doors = [])
    {
        $this->title = $title;
        $this->color = $color;
        $this->radio = $music;
        $this->doors = $doors;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @return Radio
     */
    public function getRadio(): Radio
    {
        return $this->radio;
    }

    /**
     * @return array
     */
    public function getDoors(): array
    {
        return $this->doors;
    }
}
