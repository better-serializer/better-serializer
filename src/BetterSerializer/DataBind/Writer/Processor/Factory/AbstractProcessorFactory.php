<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain\ChainMemberInterface as MetaDataMember;
use BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\ChainMemberInterface as TypeMember;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
use ReflectionException;
use LogicException;
use RuntimeException;

/**
 * Class AbstractProcessorFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory
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
     * @param SerializationContextInterface $context
     * @return ProcessorInterface
     */
    public function createFromMetaData(
        PropertyMetaDataInterface $metaData,
        SerializationContextInterface $context
    ): ProcessorInterface {
        return $this->processorFactory->createFromMetaData($metaData, $context);
    }

    /**
     * @param TypeInterface $type
     * @param SerializationContextInterface $context
     * @return ProcessorInterface
     * @throws ReflectionException|LogicException|RuntimeException
     */
    public function createFromType(TypeInterface $type, SerializationContextInterface $context): ProcessorInterface
    {
        return $this->processorFactory->createFromType($type, $context);
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
