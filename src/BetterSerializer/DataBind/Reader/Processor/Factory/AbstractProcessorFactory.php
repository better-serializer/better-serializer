<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain\ChainMemberInterface as MetaDataMember;
use BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\ChainMemberInterface as TypeMember;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use ReflectionException;
use LogicException;
use RuntimeException;

/**
 * Class AbstractProcessorFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Factory
 */
abstract class AbstractProcessorFactory
{

    /**
     * @var ProcessorFactoryInterface
     */
    private $processorFactory;

    /**
     * AbstractProcessorFactory constructor.
     * @param ProcessorFactoryInterface $processorFactory
     */
    public function __construct(ProcessorFactoryInterface $processorFactory)
    {
        $this->processorFactory = $processorFactory;
    }

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return ProcessorInterface
     */
    public function createFromMetaData(PropertyMetaDataInterface $metaData): ProcessorInterface
    {
        return $this->processorFactory->createFromMetaData($metaData);
    }

    /**
     * @param TypeInterface $type
     * @return ProcessorInterface
     * @throws ReflectionException|LogicException|RuntimeException
     */
    public function createFromType(TypeInterface $type): ProcessorInterface
    {
        return $this->processorFactory->createFromType($type);
    }

    /**
     * @param MetaDataMember $chainMember
     */
    public function addMetaDataChainMember(MetaDataMember $chainMember): void
    {
        $this->processorFactory->addMetaDataChainMember($chainMember);
    }

    /**
     * @param TypeMember $chainMember
     */
    public function addTypeChainMember(TypeMember $chainMember): void
    {
        $this->processorFactory->addTypeChainMember($chainMember);
    }
}
