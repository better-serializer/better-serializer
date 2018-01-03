<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Common;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\ParametersInterface;

/**
 * Interface ExtensionInterface
 * @author mfris
 * @package BetterSerializer\Common
 */
interface ExtensionInterface
{

    /**
     * @param ParametersInterface $parameters
     */
    public function __construct(ParametersInterface $parameters);

    /**
     * fully classified class name of processed class (ClassName::class) or a custom type name
     *
     * @return string
     */
    public static function getType(): string;

    /**
     * If the extension represents a fully custom type (with custom name), it is viable
     * to define the type which is being replaced by the custom type,
     * for the serializer to be able to pair object constructor parameters with object properties.
     *
     * @return null|string
     */
    public static function getReplacedType(): ?string;
}
