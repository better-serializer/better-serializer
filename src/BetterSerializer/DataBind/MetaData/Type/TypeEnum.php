<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

use MabeEnum\Enum;
use MabeEnum\EnumSerializableTrait;
use Serializable;

/**
 * @method static TypeEnum STRING_TYPE
 * @method static TypeEnum INTEGER_TYPE
 * @method static TypeEnum FLOAT_TYPE
 * @method static TypeEnum BOOLEAN_TYPE
 * @method static TypeEnum CLASS_TYPE
 * @method static TypeEnum INTERFACE_TYPE
 * @method static TypeEnum ARRAY_TYPE
 * @method static TypeEnum NULL_TYPE
 * @method static TypeEnum UNKNOWN_TYPE
 * @method static TypeEnum DATETIME_TYPE
 * @method static TypeEnum CUSTOM_TYPE
 * @method static TypeEnum CUSTOM_CLASS_TYPE
 * @method static TypeEnum CUSTOM_COLLECTION_TYPE
 */
final class TypeEnum extends Enum implements TypeEnumInterface, Serializable
{
    use EnumSerializableTrait;

    /**
     * @const string
     */
    public const STRING_TYPE = 'string';

    /**
     * @const string
     */
    public const INTEGER_TYPE = 'int';

    /**
     * @const string
     */
    public const FLOAT_TYPE = 'float';

    /**
     * @const string
     */
    public const BOOLEAN_TYPE = 'bool';

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
    public const ARRAY_TYPE = 'array';

    /**
     * @const string
     */
    public const NULL_TYPE = 'null';

    /**
     * @const string
     */
    public const UNKNOWN_TYPE = 'unknown';

    /**
     * @const string
     */
    public const DATETIME_TYPE = 'dateTime';

    /**
     * @const string
     */
    public const CUSTOM_TYPE = 'customType';

    /**
     * @const string
     */
    public const CUSTOM_CLASS_TYPE = 'customClass';

    /**
     * @const string
     */
    public const CUSTOM_COLLECTION_TYPE = 'customCollection';
}
