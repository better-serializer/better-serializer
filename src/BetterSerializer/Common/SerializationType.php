<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\Common;

use MabeEnum\Enum;

/**
 * Class SerializationType
 *
 * @author  mfris
 * @package BetterSerializer\Common
 * @method  static SerializationType JSON
 * @method  static SerializationType NONE
 * @method string getType
 */
final class SerializationType extends Enum implements SerializationTypeInterface
{
    /**
     * @const string
     */
    const JSON = 'json';

    /**
     * only for testing purposes
     *
     * @const string
     */
    const NONE = 'none';
}
