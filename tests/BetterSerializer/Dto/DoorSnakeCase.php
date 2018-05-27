<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Dto;

use JMS\Serializer\Annotation as JmsSerializer;

/**
 * @SuppressWarnings(PHPMD)
 */
final class DoorSnakeCase
{

    /**
     * @var bool
     * @JmsSerializer\SerializedName("parental_lock")
     * @JmsSerializer\Type("boolean")
     */
    private $parental_lock;

    /**
     * @param bool $parentalLock
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct(bool $parentalLock = false)
    {
        $this->parental_lock = $parentalLock;
    }

    /**
     * @return bool
     */
    public function hasParentalLock(): bool
    {
        return $this->parental_lock;
    }
}
