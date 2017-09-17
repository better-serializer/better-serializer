<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\MetaData;

use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;

/**
 * Class ContextualReader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
interface ContextualReaderInterface
{
    /**
     * @param string $className
     * @param SerializationContextInterface $context
     * @return MetaDataInterface
     */
    public function read(string $className, SerializationContextInterface $context): MetaDataInterface;
}
