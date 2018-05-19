<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Reader\Context;

/**
 *
 */
interface ContextInterface
{
    /**
     * @return mixed
     */
    public function getCurrentValue();

    /**
     * @param string|int $key
     * @return mixed
     */
    public function getValue($key);

    /**
     * @param mixed $deserialized
     */
    public function setDeserialized($deserialized): void;

    /**
     * @return mixed
     */
    public function getDeserialized();

    /**
     * @param mixed $key
     * @return ContextInterface|null
     */
    public function readSubContext($key): ?ContextInterface;
}
