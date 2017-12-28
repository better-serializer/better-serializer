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
use BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\ChainMemberInterface;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
use LogicException;

/**
 * Class ProcessorFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Converter
 */
final class ProcessorFactory implements ProcessorFactoryInterface
{

    /**
     * @var MetaDataMember[]
     */
    private $metaDataChainMembers;

    /**
     * @var TypeMember[]
     */
    private $typeChainMembers;

    /**
     * ProcessorFactory constructor.
     * @param MetaDataMember[] $metaDataChainMembers
     * @param TypeMember[] $typeChainMembers
     */
    public function __construct(array $metaDataChainMembers = [], array $typeChainMembers = [])
    {
        $this->metaDataChainMembers = $metaDataChainMembers;
        $this->typeChainMembers = $typeChainMembers;
    }

    /**
     * @param PropertyMetaDataInterface $metaData
     * @param SerializationContextInterface $context
     * @return ProcessorInterface
     * @throws LogicException
     */
    public function createFromMetaData(
        PropertyMetaDataInterface $metaData,
        SerializationContextInterface $context
    ): ProcessorInterface {
        foreach ($this->metaDataChainMembers as $chainMember) {
            $processor = $chainMember->create($metaData, $context);

            if ($processor) {
                return $processor;
            }
        }

        throw new LogicException("Unknown type - '" . get_class($metaData->getType()) . "'");
    }

    /**
     * @param TypeInterface $type
     * @param SerializationContextInterface $context
     * @return ProcessorInterface
     * @throws LogicException
     */
    public function createFromType(TypeInterface $type, SerializationContextInterface $context): ProcessorInterface
    {
        foreach ($this->typeChainMembers as $chainMember) {
            $processor = $chainMember->create($type, $context);

            if ($processor) {
                return $processor;
            }
        }

        throw new LogicException("Unknown type - '" . get_class($type) . "'");
    }

    /**
     * @param MetaDataMember $chainMember
     */
    public function addMetaDataChainMember(MetaDataMember $chainMember): void
    {
        $this->metaDataChainMembers[] = $chainMember;
    }

    /**
     * @param ChainMemberInterface $chainMember
     */
    public function addTypeChainMember(TypeMember $chainMember): void
    {
        $this->typeChainMembers[] = $chainMember;
    }
}
