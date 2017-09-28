<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\UseStatement\Factory;

use BetterSerializer\Reflection\UseStatement\CodeReaderInterface;
use LogicException;

/**
 * Class CodeReaderFactory
 * @author mfris
 * @package BetterSerializer\Reflection\UseStatement\Factory
 */
interface CodeReaderFactoryInterface
{
    /**
     * @param int $classOffset
     * @return CodeReaderInterface
     * @throws LogicException
     */
    public function newCodeReader(int $classOffset): CodeReaderInterface;
}
