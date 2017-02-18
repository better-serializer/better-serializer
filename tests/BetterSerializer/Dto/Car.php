<?php
declare(strict_types=1);

/**
 * @author  mfris
 */
namespace BetterSerializer\Dto;

use BetterSerializer\DataBind\MetaData\Annotations as Serializer;

/**
 * Class Car
 * @author mfris
 * @package BetterSerializer\Dto
 * @Serializer\RootName(value="car")
 */
final class Car
{

    /**
     * @var string
     * @Serializer\Property(type="string")
     */
    private $title;

    /**
     * @var string
     */
    private $color;

    /**
     * Car constructor.
     * @param string $title
     * @param string $color
     */
    public function __construct(string $title, string $color)
    {
        $this->title = $title;
        $this->color = $color;
    }

    /**
     * @Serializer'Property(name="titlex")
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
}
