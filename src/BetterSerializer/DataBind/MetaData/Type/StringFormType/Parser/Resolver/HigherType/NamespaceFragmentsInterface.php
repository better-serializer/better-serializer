<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType;

/**
 *
 */
interface NamespaceFragmentsInterface
{
    /**
     * @return string
     */
    public function getFirst(): string;

    /**
     * @return string
     */
    public function getLast(): string;

    /**
     * @return string
     */
    public function getWithoutFirst(): string;
}
