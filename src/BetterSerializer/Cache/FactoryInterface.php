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
    public function disableApcuCache(): void;

    /**
     * @param string $directory
     * @throws RuntimeException
     */
    public function setCacheDir(string $directory): void;

    /**
     * @return Cache
     * @throws InvalidArgumentException
     */
    public function getCache(): Cache;
}
