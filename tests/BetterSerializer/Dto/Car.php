<?php
/**
 * @author  mfris
 */
namespace BetterSerializer\Dto;

/**
 * Class Car
 * @author mfris
 * @package BetterSerializer\Dto
 */
final class Car
{

    /**
     * @var string
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
