<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\UseStatement;

/**
 * Interface UseStatementInterface
 * @package BetterSerializer\Reflection\UseStatement
 */
interface UseStatementInterface
{
    /**
     * @return string
     */
    public function getFqdn(): string;

    /**
     * @return string
     */
    public function getAlias(): string;

    /**
     * @return string
     */
    public function getIdentifier(): string;

    /**
     * @return string
     */
    public function getNamespace(): string;
}
