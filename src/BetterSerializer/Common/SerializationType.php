<?php
declare(strict_types=1);

/**
 * @author  mfris
 */
namespace BetterSerializer\Common;

use MabeEnum\Enum;

/**
 * Class SerializationType
 * @author mfris
 * @package BetterSerializer\Common
 * @method static SerializationType JSON
 */
final class SerializationType extends Enum
{

    public const JSON = 'json';
}
