<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\ValueWriter;

use BetterSerializer\DataBind\Writer\Context\ContextInterface;

/**
 * Interface ValueWriterInterface
 * @package BetterSerializer\DataBind\Writer\ValueWriter
 */
interface ValueWriterInterface
{

    /**
     * @param ContextInterface $context
     * @param mixed $value
     */
    public function writeValue(ContextInterface $context, $value): void;
}
