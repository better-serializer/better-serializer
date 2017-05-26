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
interface ChainMemberInterface
{
    /**
     * @param PropertyMetaDataInterface $metaData
     * @return ProcessorInterface|null
     */
    public function create(PropertyMetaDataInterface $metaData): ?ProcessorInterface;
}
