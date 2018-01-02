<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver;

use BetterSerializer\Reflection\UseStatement\UseStatements;
use BetterSerializer\Reflection\UseStatement\UseStatementsInterface;

/**
 *
 */
final class Context implements ContextInterface
{

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var UseStatementsInterface
     */
    private $useStatements;

    /**
     * @param string $namespace
     * @param UseStatementsInterface $useStatements
     */
    public function __construct(string $namespace = '', UseStatementsInterface $useStatements = null)
    {
        if (!$useStatements) {
            $useStatements = new UseStatements([]);
        }

        $this->namespace = trim($namespace);
        $this->useStatements = $useStatements;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return UseStatementsInterface
     */
    public function getUseStatements(): UseStatementsInterface
    {
        return $this->useStatements;
    }
}
