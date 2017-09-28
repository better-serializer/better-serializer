<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;

/**
 * Interface ParamProcessorInterface
 * @package BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor
 */
interface ParamProcessorInterface
{

    /**
     * @param ContextInterface $context
     * @return mixed
     */
    public function processParam(ContextInterface $context);
}
