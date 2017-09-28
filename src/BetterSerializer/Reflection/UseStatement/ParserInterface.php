<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\UseStatement;

/**
 * Class Parser
 * @author mfris
 * @package BetterSerializer\Reflection\UseStatement
 */
interface ParserInterface
{
    /**
     * @param string $useStatementsString
     * @return UseStatement[]
     */
    public function parseUseStatements(string $useStatementsString): array;
}
