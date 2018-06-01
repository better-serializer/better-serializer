<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Dto;

use BetterSerializer\DataBind\MetaData\Annotations as Serializer;
use JMS\Serializer\Annotation as JmsSerializer;

/**
 *
 */
final class Radio implements RadioInterface
{

    /**
     * @var string
     * @Serializer\Groups({"group1","default"})
     * @JmsSerializer\Type("string")
     */
    private $brand;

    /**
     * Radio constructor.
     * @param string $brand
     */
    public function __construct(string $brand)
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
