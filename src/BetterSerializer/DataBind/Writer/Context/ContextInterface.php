<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Writer\Context;

/**
 * Class ContextInterface
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\ValueWriter
 */
interface ContextInterface
{

    /**
     * @param string|int $key
     * @param mixed $value
     * @return void
     */
    public function write($key, $value): void;

    /**
     * @return ContextInterface
     */
    public function createSubContext(): ContextInterface;

    /**
     * @param mixed $key
     * @param ContextInterface $context
     */
    public function mergeSubContext($key, ContextInterface $context): void;

    /**
     * @return mixed
     */
    public function getRawData();

    /**
     * @return string
     */
    public function getData();
}
