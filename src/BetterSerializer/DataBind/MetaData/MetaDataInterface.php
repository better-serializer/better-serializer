<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData;

/**
 * Class MetaData
 *
 * @author  mfris
 * @package BetterSerializer\DataBind\MetaData
 */
interface MetaDataInterface
{
    /**
     * @return ClassMetadataInterface
     */
    public function getClassMetadata(): ClassMetadataInterface;

    /**
     * @return PropertyMetaDataInterface[]
     */
    public function getPropertiesMetadata(): array;
}
