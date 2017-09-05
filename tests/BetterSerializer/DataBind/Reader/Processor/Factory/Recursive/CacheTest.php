<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\Recursive;

use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CacheTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Factory\Recursive
 */
class CacheTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $key = 'test';
        $processor = $this->createMock(ProcessorInterface::class);

        $cache = new Cache();
        $cache->setProcessor($key, $processor);

        self::assertSame($processor, $cache->getProcessor($key));
    }
}
