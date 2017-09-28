<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor\Chain;

use BetterSerializer\DataBind\MetaData\Model\PropertyTuple\PropertyWithConstructorParamTupleInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\ParamProcessorInterface;

/**
 * Class ChainedParamProcessorFactoryInterface
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor\Chain
 */
interface ChainedParamProcessorFactoryInterface
{

    /**
     * @param PropertyWithConstructorParamTupleInterface $tuple
     * @return bool
     */
    public function isApplicable(PropertyWithConstructorParamTupleInterface $tuple): bool;

    /**
     * @param PropertyWithConstructorParamTupleInterface $tuple
     * @return ParamProcessorInterface
     */
    public function newChainedParamProcessorFactory(
        PropertyWithConstructorParamTupleInterface $tuple
    ): ParamProcessorInterface;
}
