<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\Common;

use MabeEnum\Enum;

/**
 * @method  static SerializationType JSON
 * @method  static SerializationType PHP_ARRAY
 * @method  static SerializationType NONE
 * @method string getType
 */
final class SerializationType extends Enum implements SerializationTypeInterface
{
    /**
     * @const string
     */
    public const JSON = 'json';

    /**
     * @const string
     */
    public const PHP_ARRAY = 'php_array';

    /**
     * only for testing purposes
     *
     * @const string
     */
    public const NONE = 'none';
}
