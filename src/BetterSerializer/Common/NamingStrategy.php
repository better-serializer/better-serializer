<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\Common;

use MabeEnum\Enum;

/**
 * @method  static NamingStrategy IDENTITY
 * @method  static NamingStrategy CAMEL_CASE
 * @method  static NamingStrategy SNAKE_CASE
 * @method string getType
 */
final class NamingStrategy extends Enum implements NamingStrategyInterface
{
    /**
     * @const string
     */
    public const IDENTITY = 'identity';

    /**
     * @const string
     */
    public const CAMEL_CASE = 'camel_case';

    /**
     * @const string
     */
    public const SNAKE_CASE = 'snake_case';
}
