<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

use MabeEnum\Enum;

/**
 * Class Type
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 * @method static TypeEnum STRING
 * @method static TypeEnum INTEGER
 * @method static TypeEnum FLOAT
 * @method static TypeEnum BOOLEAN
 * @method static TypeEnum OBJECT
 * @method static TypeEnum NULL
 */
final class TypeEnum extends Enum
{

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
    const NULL = 'null';
}
