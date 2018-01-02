<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType;

/**
 *
 */
interface NamespaceFragmentsParserInterface
{

    /**
     * @param string $potentialClass
     * @return NamespaceFragmentsInterface
     */
    public function parse(string $potentialClass): NamespaceFragmentsInterface;
}
