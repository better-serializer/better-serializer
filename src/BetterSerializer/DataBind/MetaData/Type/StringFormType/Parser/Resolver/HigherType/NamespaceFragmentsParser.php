<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType;

/**
 *
 */
final class NamespaceFragmentsParser implements NamespaceFragmentsParserInterface
{

    /**
     * @param string $potentialClass
     * @return NamespaceFragmentsInterface
     */
    public function parse(string $potentialClass): NamespaceFragmentsInterface
    {
        $parts = explode('\\', ltrim($potentialClass, '\\'));
        $first = $parts[0];
        $last = array_pop($parts);
        $withoutFirst = preg_replace("/^\\\?" . $first ."\\\?/", '', $potentialClass);

        return new NamespaceFragments($first, $last, $withoutFirst);
    }
}
