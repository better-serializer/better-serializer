<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Writer\Context\ContextInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\Recursive\CacheInterface;
use RuntimeException;

/**
 * Class Cached
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor
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
     * @param mixed $data
     * @throws RuntimeException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function process(ContextInterface $context, $data): void
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
