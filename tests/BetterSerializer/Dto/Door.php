<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Dto;

use JMS\Serializer\Annotation as JmsSerializer;

/**
 * Class Door
 * @author mfris
 * @package BetterSerializer\Dto
 * @SuppressWarnings(PHPMD)
 */
final class Door
{

    /**
     * @var bool
     * @JmsSerializer\SerializedName("parentalLock")
     */
    private $parentalLock;

    /**
     * Door constructor.
     * @param bool $parentalLock
     */
    public function __construct(bool $parentalLock = false)
    {
        $this->parentalLock = $parentalLock;
    }

    /**
     * @return bool
     */
    public function getParentalLock(): bool
    {
        return $this->parentalLock;
    }
}
