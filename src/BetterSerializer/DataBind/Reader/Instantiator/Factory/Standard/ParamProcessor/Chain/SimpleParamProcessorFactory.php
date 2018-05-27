<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor\Chain;

use BetterSerializer\DataBind\MetaData\Model\PropertyTuple\PropertyWithConstructorParamTupleInterface;
use BetterSerializer\DataBind\MetaData\Type\SimpleTypeInterface;
use BetterSerializer\DataBind\Naming\PropertyNameTranslator\TranslatorInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\ParamProcessorInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\SimpleParamProcessor;

/**
 *
 */
final class SimpleParamProcessorFactory implements ChainedParamProcessorFactoryInterface
{

    /**
     * @var TranslatorInterface
     */
    private $nameTranslator;

    /**
     * @param TranslatorInterface $translatorName
     */
    public function __construct(TranslatorInterface $translatorName)
    {
        $this->nameTranslator = $translatorName;
    }

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
        $serializationName = $this->nameTranslator->translate($tuple->getPropertyMetaData());

        return new SimpleParamProcessor($serializationName);
    }
}
