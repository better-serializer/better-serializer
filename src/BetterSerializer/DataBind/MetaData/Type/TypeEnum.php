<?php
declare(strict_types = 1);

/**
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
 * @method static TypeEnum ARRAY
 * @method static TypeEnum NULL
 * @method static TypeEnum UNKNOWN
 * @method static TypeEnum DATETIME
 */
final class TypeEnum extends Enum implements Serializable
{
    use EnumSerializableTrait;

    /**
     * @const string
     */
    const STRING = 'string';

    /**
     * @const string
     */
    const INTEGER = 'int';

    /**
     * @const string
     */
    const FLOAT = 'float';

    /**
     * @const string
     */
    const BOOLEAN = 'bool';

    /**
     * @const string
     */
    const OBJECT = 'object';

    /**
     * @const string
     */
    const ARRAY = 'array';

    /**
     * @const string
     */
    const NULL = 'null';

    /**
     * @const string
     */
    const UNKNOWN = 'unknown';

    /**
     * @const string
     */
    const DATETIME = 'dateTime';
}
