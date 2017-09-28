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
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 * Class ProcessorFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory
 */
interface ProcessorFactoryInterface
{
    /**
     * @param PropertyMetaDataInterface $metaData
     * @param SerializationContextInterface $context
     * @return ProcessorInterface
     */
    public function createFromMetaData(
        PropertyMetaDataInterface $metaData,
        SerializationContextInterface $context
    ): ProcessorInterface;

    /**
     * @param TypeInterface $type
     * @param SerializationContextInterface $context
     * @return ProcessorInterface
     * @throws ReflectionException
     * @throws LogicException
     * @throws RuntimeException
     */
    public function createFromType(TypeInterface $type, SerializationContextInterface $context): ProcessorInterface;

    /**
     * @param MetaDataMember $chainMember
     */
    public function addMetaDataChainMember(MetaDataMember $chainMember): void;

    /**
     * @param TypeMember $chainMember
     */
    public function addTypeChainMember(TypeMember $chainMember): void;
}
