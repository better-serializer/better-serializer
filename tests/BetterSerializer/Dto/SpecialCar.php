<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Dto;

/**
 * Class SpecialCar
 * @author mfris
 * @package BetterSerializer\Dto
 */
final class SpecialCar extends Car implements SpecialCarInterface
{

    /**
     * @var string
     */
    private $special;

    /**
     * Car constructor.
     * @param string $title
     * @param string $color
     * @param Radio $radio
     * @param string $special
     * @param Door[] $doors
     */
    public function __construct(string $title, string $color, Radio $radio, string $special, array $doors = [])
    {
        parent::__construct($title, $color, $radio, $doors);
        $this->special = $special;
    }

    /**
     * @return string
     */
    public function getSpecial(): string
    {
        return $this->special;
    }
}
