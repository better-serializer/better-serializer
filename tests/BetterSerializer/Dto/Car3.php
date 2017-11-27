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
final class Car3
{

    /**
     * @var bool
     * @Serializer\Property(type="BooleanString")
     */
    private $isForKids;

    /**
     * @param bool $isForKids
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct(bool $isForKids = true)
    {
        $this->isForKids = $isForKids;
    }

    /**
     * @return bool
     */
    public function isForKids(): bool
    {
        return $this->isForKids;
    }
}
