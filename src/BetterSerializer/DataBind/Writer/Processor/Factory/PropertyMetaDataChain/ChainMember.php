<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;

/**
 * Class ChainMember
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Converter\PropertyMetaDataChain
 */
abstract class ChainMember implements ChainMemberInterface
{

    /**
     * @param PropertyMetaDataInterface $metaData
     * @param SerializationContextInterface $context
     * @return ProcessorInterface|null
     */
    public function create(
        PropertyMetaDataInterface $metaData,
        SerializationContextInterface $context
    ): ?ProcessorInterface {
        if (!$this->isCreatable($metaData)) {
            return null;
        }

        return $this->createProcessor($metaData, $context);
    }

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return bool
     */
    abstract protected function isCreatable(PropertyMetaDataInterface $metaData): bool;

    /**
     * @param PropertyMetaDataInterface $metaData
     * @param SerializationContextInterface $context
     * @return ProcessorInterface
     */
    abstract protected function createProcessor(
        PropertyMetaDataInterface $metaData,
        SerializationContextInterface $context
    ): ProcessorInterface;
}
