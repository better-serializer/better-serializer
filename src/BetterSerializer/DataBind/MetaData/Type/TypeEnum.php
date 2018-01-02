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
 * Class Type
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 * @method static TypeEnum STRING
 * @method static TypeEnum INTEGER
 * @method static TypeEnum FLOAT
 * @method static TypeEnum BOOLEAN
 * @method static TypeEnum OBJECT
 * @method static TypeEnum INTERFACE
 * @method static TypeEnum ARRAY
 * @method static TypeEnum NULL
 * @method static TypeEnum UNKNOWN
 * @method static TypeEnum DATETIME
 * @method static TypeEnum CUSTOM_TYPE
 * @method static TypeEnum CUSTOM_OBJECT
 * @method static TypeEnum CUSTOM_COLLECTION
 */
final class TypeEnum extends Enum implements TypeEnumInterface, Serializable
{
    use EnumSerializableTrait;

    /**
     * @const string
     */
    public const STRING = 'string';

    /**
     * @const string
     */
    public const INTEGER = 'int';

    /**
     * @const string
     */
    public const FLOAT = 'float';

    /**
     * @const string
     */
    public const BOOLEAN = 'bool';

    /**
     * @const string
     */
    public const OBJECT = 'object';

    /**
     * @const string
     */
    public const INTERFACE = 'interface';

    /**
     * @const string
     */
    public const ARRAY = 'array';

    /**
     * @const string
     */
    public const NULL = 'null';

    /**
     * @const string
     */
    public const UNKNOWN = 'unknown';

    /**
     * @const string
     */
    public const DATETIME = 'dateTime';

    /**
     * @const string
     */
    public const CUSTOM_TYPE = 'customType';

    /**
     * @const string
     */
    public const CUSTOM_OBJECT = 'customObject';

    /**
     * @const string
     */
    public const CUSTOM_COLLECTION = 'customCollection';
}
