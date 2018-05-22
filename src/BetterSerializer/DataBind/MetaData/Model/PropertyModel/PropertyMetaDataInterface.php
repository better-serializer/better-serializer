<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Model\PropertyModel;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\Reflection\ReflectionPropertyInterface;

/**
 *
 */
interface PropertyMetaDataInterface
{

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getSerializationName(): string;

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface;

    /**
     * @return string[]
     */
    public function getGroups(): array;

    /**
     * @return ReflectionPropertyInterface
     *
     */
    public function getReflectionProperty(): ReflectionPropertyInterface;
}
