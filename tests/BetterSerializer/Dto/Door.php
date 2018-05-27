<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Dto;

use JMS\Serializer\Annotation as JmsSerializer;

/**
 *
 */
final class Door
{

    /**
     * @var bool
     * @JmsSerializer\SerializedName("parentalLock")
     * @JmsSerializer\Type("boolean")
     */
    private $parentalLock;

    /**
     * @param bool $parentalLock
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct(bool $parentalLock = false)
    {
        $this->parentalLock = $parentalLock;
    }

    /**
     * @return bool
     */
    public function hasParentalLock(): bool
    {
        return $this->parentalLock;
    }
}
