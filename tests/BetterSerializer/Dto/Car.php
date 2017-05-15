<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\Dto;

use BetterSerializer\DataBind\MetaData\Annotations as Serializer;

/**
 * Class Car
 * @author mfris
 * @package BetterSerializer\Dto
 * @Serializer\RootName(value="car")
 */
final class Car implements CarInterface
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
     * @var Radio
     */
    private $radio;

    /**
     * Car constructor.
     * @param string $title
     * @param string $color
     * @param Radio $radio
     */
    public function __construct(string $title, string $color, Radio $radio)
    {
        $this->title = $title;
        $this->color = $color;
        $this->radio = $radio;
    }

    /**
     * @Serializer\Property(name="titlex")
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
}
