<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer;

/**
 * Class SerializationContext
 * @author mfris
 * @package BetterSerializer\DataBind\Writer
 */
final class SerializationContext implements SerializationContextInterface
{

    /**
     * @var string[]
     */
    private $groups;

    /**
     * SerializationContext constructor.
     * @param array $groups
     */
    public function __construct(array $groups = ['default'])
    {
        $this->groups = $groups;
    }

    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }
}
