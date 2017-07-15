<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor;

use BetterSerializer\DataBind\MetaData\Model\PropertyTuple\PropertyWithConstructorParamTupleInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor\Chain as ChainNamespace;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\ParamProcessorInterface;
use LogicException;
use RuntimeException;

/**
 * Class ParamProcessorFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor
 */
final class ParamProcessorFactory implements ParamProcessorFactoryInterface
{

    /**
     * @var ChainNamespace\ChainedParamProcessorFactoryInterface[]
     */
    private $chainedParamProcessorFactories;

    /**
     * ParamProcessorFactory constructor.
     * @param ChainNamespace\ChainedParamProcessorFactoryInterface[] $chainedFactories
     * @throws LogicException
     */
    public function __construct(array $chainedFactories)
    {
        if (empty($chainedFactories)) {
            throw new LogicException('Chained factories not provided.');
        }

        $this->chainedParamProcessorFactories = $chainedFactories;
    }

    /**
     * @param PropertyWithConstructorParamTupleInterface $tuple
     * @return ParamProcessorInterface
     * @throws RuntimeException
     */
    public function newParamProcessor(PropertyWithConstructorParamTupleInterface $tuple): ParamProcessorInterface
    {
        foreach ($this->chainedParamProcessorFactories as $paramProcFactory) {
            if ($paramProcFactory->isApplicable($tuple)) {
                return $paramProcFactory->newChainedParamProcessorFactory($tuple);
            }
        }

        throw new RuntimeException(
            sprintf(
                "Unable to create constructor param processor for type: '%s'.",
                (string) $tuple->getType()
            )
        );
    }
}
