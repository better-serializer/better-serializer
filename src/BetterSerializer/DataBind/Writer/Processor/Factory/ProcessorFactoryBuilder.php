<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory;

use BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain\ChainMemberInterface as MetaDataMember;
use BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\ChainMemberInterface as TypeMember;

/**
 *
 */
final class ProcessorFactoryBuilder
{

    /**
     * @param ProcessorFactoryInterface $processorFactory
     * @param MetaDataMember[] $metaDataChainMembers
     * @param TypeMember[] $typeChainMembers
     * @return ProcessorFactoryInterface
     */
    public static function build(
        ProcessorFactoryInterface $processorFactory,
        array $metaDataChainMembers,
        array $typeChainMembers
    ): ProcessorFactoryInterface {
        self::registerPropertyMetadataMembers($processorFactory, $metaDataChainMembers);
        self::registerTypeMembers($processorFactory, $typeChainMembers);

        return $processorFactory;
    }

    /**
     * @param ProcessorFactoryInterface $processorFactory
     * @param MetaDataMember[] $metaDataChainMembers
     */
    private static function registerPropertyMetadataMembers(
        ProcessorFactoryInterface $processorFactory,
        array $metaDataChainMembers
    ): void {
        foreach ($metaDataChainMembers as $metaDataChainMember) {
            $processorFactory->addMetaDataChainMember($metaDataChainMember);
        }
    }

    /**
     * @param ProcessorFactoryInterface $processorFactory
     * @param TypeMember[] $typeChainMembers
     */
    private static function registerTypeMembers(
        ProcessorFactoryInterface $processorFactory,
        array $typeChainMembers
    ): void {
        foreach ($typeChainMembers as $typeChainMember) {
            $processorFactory->addTypeChainMember($typeChainMember);
        }
    }
}
