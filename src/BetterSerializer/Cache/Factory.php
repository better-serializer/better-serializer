<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Cache;

use Doctrine\Common\Cache\ApcuCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\ChainCache;
use Doctrine\Common\Cache\FilesystemCache;
use InvalidArgumentException;
use RuntimeException;

/**
 * Class Factory
 * @author mfris
 * @package BetterSerializer\Cache
 */
final class Factory implements FactoryInterface
{

    /**
     * @var bool
     */
    private $apcuEnabled = false;

    /**
     * @var string
     */
    private $cacheDir = '';

    /**
     *
     */
    public function enableApcuCache(): void
    {
        $this->apcuEnabled = true;
    }

    /**
     *
     */
    public function disableApcuCache(): void
    {
        $this->apcuEnabled = false;
    }

    /**
     * @param string $directory
     * @throws RuntimeException
     */
    public function setCacheDir(string $directory): void
    {
        if (!file_exists($directory) || !is_dir($directory)) {
            throw new RuntimeException(sprintf('Invalid directory: %s', $directory));
        }

        $this->cacheDir = $directory;
    }

    /**
     * @return Cache
     * @throws InvalidArgumentException
     */
    public function getCache(): Cache
    {
        $cacheProviders = [
            new ArrayCache(),
        ];

        if ($this->apcuEnabled) {
            $cacheProviders[] = new ApcuCache();
        } elseif ($this->cacheDir !== '') {
            $cacheProviders[] = new FilesystemCache($this->cacheDir);
        }

        $cache = new ChainCache($cacheProviders);
        $cache->setNamespace('better-serializer');

        return $cache;
    }
}
