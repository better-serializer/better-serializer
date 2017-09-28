<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Writer\Context\ContextInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\Recursive\CacheInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Class CachedTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor
 */
class CachedTest extends TestCase
{

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage This method shouldn't be called.
     */
    public function testProcessShouldThrow(): void
    {
        $key = 'test';
        $cache = $this->createMock(CacheInterface::class);
        $context = $this->createMock(ContextInterface::class);
        $data = null;

        $cached = new Cached($cache, $key);
        $cached->process($context, $data);
    }

    /**
     *
     */
    public function testGetProcessor(): void
    {
        $key = 'test';
        $processor = $this->createMock(ProcessorInterface::class);
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('getProcessor')
            ->with($key)
            ->willReturn($processor);

        $cached = new Cached($cache, $key);

        self::assertSame($processor, $cached->getProcessor());
    }
}
