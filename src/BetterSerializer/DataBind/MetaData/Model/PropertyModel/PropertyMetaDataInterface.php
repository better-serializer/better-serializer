<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Model\PropertyModel;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use ReflectionProperty;

/**
 * Interface PropertyMetadataInterface
 *
 * @package BetterSerializer\DataBind\MetaData
 */
interface PropertyMetaDataInterface
{

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface;

    /**
     * @return string
     */
    public function getOutputKey(): string;
}
