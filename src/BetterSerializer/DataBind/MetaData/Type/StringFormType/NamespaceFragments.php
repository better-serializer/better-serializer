<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType;

/**
 * Class NamespaceFragments
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\StringFormType
 */
final class NamespaceFragments
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
     * NamespaceFragments constructor.
     * @param string $namespacedType
     */
    public function __construct(string $namespacedType)
    {
        $this->resloveFragments($namespacedType);
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

    /**
     * @param string $namespacedType
     */
    private function resloveFragments(string $namespacedType): void
    {
        $parts = explode("\\", ltrim($namespacedType, '\\'));
        $this->first = $parts[0];
        $this->last = array_pop($parts);
        $this->withoutFirst = preg_replace("/^\\\?" . $this->first ."\\\?/", '', $namespacedType);
    }
}
