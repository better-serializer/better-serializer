<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\MetaData\PropertyMetaDataInterface;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;

/**
 * Class ChainMember
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain
 */
abstract class ChainMember implements ChainMemberInterface
{

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return ProcessorInterface|null
     */
    public function create(PropertyMetaDataInterface $metaData): ?ProcessorInterface
    {
        if (!$this->isCreatable($metaData)) {
            return null;
        }

        return $this->createProcessor($metaData);
    }

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return bool
     */
    abstract protected function isCreatable(PropertyMetaDataInterface $metaData): bool;

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return ProcessorInterface
     */
    abstract protected function createProcessor(PropertyMetaDataInterface $metaData): ProcessorInterface;
}
