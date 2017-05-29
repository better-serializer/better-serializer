<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Reader\Context;

/**
 * Class ContextInterface
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\ValueWriter
 */
interface ContextInterface
{

    /**
     * @param string|int $key
     * @return mixed
     */
    public function readValue($key);

    /**
     * @param mixed $key
     * @return ContextInterface
     */
    public function readSubContext($key): ContextInterface;
}
