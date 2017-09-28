<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor;

use BetterSerializer\DataBind\MetaData\Model\PropertyTuple\PropertyWithConstructorParamTupleInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\ParamProcessorInterface;

/**
 * Interface ParamProcessorFactoryInterface
 * @package BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor
 */
interface ParamProcessorFactoryInterface
{

    /**
     * @param PropertyWithConstructorParamTupleInterface $tuple
     * @return ParamProcessorInterface
     */
    public function newParamProcessor(PropertyWithConstructorParamTupleInterface $tuple): ParamProcessorInterface;
}
