<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Dto;

use BetterSerializer\DataBind\MetaData\Annotations as Serializer;
use Doctrine\Common\Collections\Collection;

/**
 *
 */
final class Car3
{

    /**
     * @var Collection
     * @Serializer\Property(type="Collection<Door>(paramA='string', paramB=7)")
     */
    private $doors;

    /**
     * @var bool
     * @Serializer\Property(type="BooleanString(param1='string', param2=6)")
     */
    private $isForKids;

    /**
     * @param Collection $doors
     * @param bool $isForKids
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct(Collection $doors, bool $isForKids = true)
    {
        $this->doors = $doors;
        $this->isForKids = $isForKids;
    }

    /**
     * @return Collection
     */
    public function getDoors(): Collection
    {
        return $this->doors;
    }

    /**
     * @return bool
     */
    public function isForKids(): bool
    {
        return $this->isForKids;
    }
}
