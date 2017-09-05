<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor;

/**
 * Interface ComplexParamProcessorInterface
 * @package BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor
 */
interface ComplexParamProcessorInterface extends ParamProcessorInterface
{

    /**
     * @return void
     */
    public function resolveRecursiveProcessors(): void;
}
