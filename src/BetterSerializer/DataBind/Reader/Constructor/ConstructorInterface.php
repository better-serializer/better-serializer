<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Constructor;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;

/**
 * Interface ConstructorInterface
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Constructor
 */
interface ConstructorInterface
{

    /**
     * @param ContextInterface $context
     * @return mixed
     */
    public function construct(ContextInterface $context);
}
