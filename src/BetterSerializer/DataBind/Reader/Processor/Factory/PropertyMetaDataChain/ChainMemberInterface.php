<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;

/**
 *
 */
interface ChainMemberInterface
{
    /**
     * @param PropertyMetaDataInterface $metaData
     * @return ProcessorInterface|null
     */
    public function create(PropertyMetaDataInterface $metaData): ?ProcessorInterface;
}
