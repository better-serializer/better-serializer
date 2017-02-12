<?php
/**
 * @author  mfris
 */
declare(strict_types=1);

namespace BetterSerializer\Common;

use MabeEnum\Enum;

/**
 * Class SerializationType
 * @author mfris
 * @package BetterSerializer\Common
 * @method SerializationType JSON
 */
final class SerializationType extends Enum
{

    public const JSON = 'json';
}
