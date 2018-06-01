<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Dto;

use BetterSerializer\DataBind\MetaData\Annotations as Serializer;

/**
 *
 */
final class Aliases
{

    /**
     * @var integer
     */
    private $integer1;

    /**
     * @var integer
     * @Serializer\Property(type="integer")
     */
    private $integer2;

    /**
     * @param int $integer1
     * @param int $integer2
     */
    public function __construct(int $integer1, int $integer2)
    {
        $this->integer1 = $integer1;
        $this->integer2 = $integer2;
    }

    /**
     * @return int
     */
    public function getInteger1(): int
    {
        return $this->integer1;
    }

    /**
     * @return int
     */
    public function getInteger2(): int
    {
        return $this->integer2;
    }
}
