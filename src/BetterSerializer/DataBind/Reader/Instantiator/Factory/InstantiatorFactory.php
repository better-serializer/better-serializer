<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Factory;

use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use LogicException;
use RuntimeException;

/**
 * Class InstantiatorFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator\Factory
 */
final class InstantiatorFactory implements InstantiatorFactoryInterface
{

    /**
     * @var ChainedInstantiatorFactoryInterface[]
     */
    private $chainedFactories;

    /**
     * InstantiatorFactory constructor.
     * @param ChainedInstantiatorFactoryInterface[] $chainedFactories
     * @throws LogicException
     */
    public function __construct(array $chainedFactories)
    {
        if (empty($chainedFactories)) {
            throw new LogicException('Chained factories not provided.');
        }

        $this->chainedFactories = $chainedFactories;
    }

    /**
     * @param MetaDataInterface $metaData
     * @return InstantiatorResultInterface
     * @throws RuntimeException
     */
    public function newInstantiator(MetaDataInterface $metaData): InstantiatorResultInterface
    {
        foreach ($this->chainedFactories as $chainedFactory) {
            if ($chainedFactory->isApplicable($metaData)) {
                return $chainedFactory->newInstantiator($metaData);
            }
        }

        throw new RuntimeException(
            sprintf(
                "Unable to create instantiator for class: '%s'.",
                $metaData->getClassMetadata()->getClassName()
            )
        );
    }
}
