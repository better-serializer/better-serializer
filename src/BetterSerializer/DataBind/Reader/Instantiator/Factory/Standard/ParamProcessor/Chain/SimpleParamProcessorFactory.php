<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor\Chain;

use BetterSerializer\DataBind\MetaData\Model\PropertyTuple\PropertyWithConstructorParamTupleInterface;
use BetterSerializer\DataBind\MetaData\Type\SimpleTypeInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\ParamProcessorInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\SimpleParamProcessor;

/**
 * Class SimpleParamProcessorFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor
 */
final class SimpleParamProcessorFactory implements ChainedParamProcessorFactoryInterface
{

    /**
     * @param PropertyWithConstructorParamTupleInterface $tuple
     * @return bool
     */
    public function isApplicable(PropertyWithConstructorParamTupleInterface $tuple): bool
    {
        return $tuple->getType() instanceof SimpleTypeInterface;
    }

    /**
     * @param PropertyWithConstructorParamTupleInterface $tuple
     * @return ParamProcessorInterface
     */
    public function newChainedParamProcessorFactory(
        PropertyWithConstructorParamTupleInterface $tuple
    ): ParamProcessorInterface {
        $key = $tuple->getOutputKey();

        return new SimpleParamProcessor($key);
    }
}
