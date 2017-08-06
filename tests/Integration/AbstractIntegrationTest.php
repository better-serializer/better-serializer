<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace Integration;

use BetterSerializer\Serializer;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

/**
 * @author mfris
 * @package Integration
 */
abstract class AbstractIntegrationTest extends TestCase
{

    /**
     * @var Container
     */
    private static $container;

    /**
     * @var Serializer
     */
    private static $serializer;

    /**
     *
     */
    public static function setUpBeforeClass()
    {
        self::$container = require dirname(__DIR__) . '/../dev/di.pimple.php';
    }

    /**
     * @return Serializer
     */
    protected function getSerializer(): Serializer
    {
        if (self::$serializer === null) {
            self::$serializer = self::$container->offsetGet(Serializer::class);
        }

        return self::$serializer;
    }
}
