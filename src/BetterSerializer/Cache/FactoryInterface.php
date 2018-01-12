<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Cache;

use Doctrine\Common\Cache\Cache;
use InvalidArgumentException;
use RuntimeException;

/**
 * Class Factory
 * @author mfris
 * @package BetterSerializer\Cache
 */
interface FactoryInterface
{
    /**
     *
     */
    public function enableApcuCache(): void;

    /**
     *
     */
    public function disableCache(): void;

    /**
     * @param string $path
     * @throws RuntimeException
     */
    public function enableFileSystemCache(string $path): void;

    /**
     * @return Cache
     * @throws InvalidArgumentException
     */
    public function getCache(): Cache;
}
