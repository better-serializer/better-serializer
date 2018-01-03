<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

use MabeEnum\Enum;

/**
 * @method static TypeClassEnum PRIMITIVE_TYPE
 * @method static TypeClassEnum CLASS_TYPE
 * @method static TypeClassEnum INTERFACE_TYPE
 * @method static TypeClassEnum EXTENSION_TYPE
 * @method static TypeClassEnum UNKNOWN_TYPE
 */
final class TypeClassEnum extends Enum implements TypeClassEnumInterface
{

    /**
     * @const string
     */
    public const PRIMITIVE_TYPE = 'primitive';

    /**
     * @const string
     */
    public const CLASS_TYPE = 'class';

    /**
     * @const string
     */
    public const INTERFACE_TYPE = 'interface';

    /**
     * @const string
     */
    public const EXTENSION_TYPE = 'extension ';

    /**
     * @const string
     */
    public const UNKNOWN_TYPE = 'unknown';
}
