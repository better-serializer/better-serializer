<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver;

use BetterSerializer\Reflection\UseStatement\UseStatementsInterface;

/**
 *
 */
interface ContextInterface
{

    /**
     * @return string
     */
    public function getNamespace(): string;

    /**
     * @return UseStatementsInterface
     */
    public function getUseStatements(): UseStatementsInterface;
}
