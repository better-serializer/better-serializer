<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType;

/**
 *
 */
final class NamespaceFragments implements NamespaceFragmentsInterface
{

    /**
     * @var string
     */
    private $first;

    /**
     * @var string
     */
    private $last;

    /**
     * @var string
     */
    private $withoutFirst;

    /**
     * @param string $first
     * @param string $last
     * @param string $withoutFirst
     */
    public function __construct(string $first, string $last, string $withoutFirst)
    {
        $this->first = trim($first);
        $this->last = trim($last);
        $this->withoutFirst = trim($withoutFirst);
    }

    /**
     * @return string
     */
    public function getFirst(): string
    {
        return $this->first;
    }

    /**
     * @return string
     */
    public function getLast(): string
    {
        return $this->last;
    }

    /**
     * @return string
     */
    public function getWithoutFirst(): string
    {
        return $this->withoutFirst;
    }
}
