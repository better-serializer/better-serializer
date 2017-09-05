<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\Recursive\CacheInterface;
use RuntimeException;

/**
 * Class Cached
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor
 */
final class Cached implements CachedProcessorInterface
{

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var string
     */
    private $key;

    /**
     * Cached constructor.
     * @param CacheInterface $cache
     * @param string $key
     */
    public function __construct(CacheInterface $cache, $key)
    {
        $this->cache = $cache;
        $this->key = $key;
    }

    /**
     * @param ContextInterface $context
     * @throws RuntimeException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function process(ContextInterface $context): void
    {
        throw new RuntimeException("This method shouldn't be called.");
    }

    /**
     * @return ProcessorInterface
     */
    public function getProcessor(): ProcessorInterface
    {
        return $this->cache->getProcessor($this->key);
    }
}
