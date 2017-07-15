<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;

/**
 * Interface ConstructorInterface
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator
 */
interface InstantiatorInterface
{

    /**
     * @param ContextInterface $context
     * @return mixed
     */
    public function instantiate(ContextInterface $context);
}
