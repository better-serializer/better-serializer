<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Dto;

/**
 * Class Radio
 * @author mfris
 * @package BetterSerializer\Dto
 */
final class Radio implements RadioInterface
{

    /**
     * @var string
     */
    private $brand;

    /**
     * Radio constructor.
     * @param string $brand
     */
    public function __construct($brand)
    {
        $this->brand = $brand;
    }

    /**
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }
}
