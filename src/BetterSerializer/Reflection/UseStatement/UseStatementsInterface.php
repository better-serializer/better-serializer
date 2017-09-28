<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\UseStatement;

use LogicException;

/**
 * Class UseStatements
 * @author mfris
 * @package BetterSerializer\Reflection\UseStatement
 */
interface UseStatementsInterface
{

    /**
     * @param string $identifier
     * @return bool
     */
    public function hasByIdentifier(string $identifier): bool;

    /**
     * @param string $identifier
     * @return UseStatementInterface|null
     * @throws LogicException
     */
    public function getByIdentifier(string $identifier): ?UseStatementInterface;

    /**
     * @param string $alias
     * @return UseStatementInterface|null
     * @throws LogicException
     */
    public function getByAlias(string $alias): ?UseStatementInterface;

    /**
     * @param string $alias
     * @return bool
     */
    public function hasByAlias(string $alias): bool;
}
