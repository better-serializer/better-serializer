<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Cache;

use BetterSerializer\Cache\Config\ApcuConfig;
use BetterSerializer\Cache\Config\ConfigInterface;
use BetterSerializer\Cache\Config\FileSystemConfig;
use BetterSerializer\Cache\Config\NullConfig;
use Doctrine\Common\Cache\ApcuCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\ChainCache;
use Doctrine\Common\Cache\FilesystemCache;
use InvalidArgumentException;
use RuntimeException;

/**
 *
 */
final class Factory implements FactoryInterface
{

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     */
    public function __construct()
    {
        $this->disableCache();
    }

    /**
     *
     */
    public function enableApcuCache(): void
    {
        $this->config = new ApcuConfig();
    }

    /**
     * @param string $path
     * @throws RuntimeException
     */
    public function enableFileSystemCache(string $path): void
    {
        if (!file_exists($path) || !is_dir($path)) {
            throw new RuntimeException(sprintf('Invalid directory: %s', $path));
        }

        $this->config = new FileSystemConfig($path);
    }

    /**
     *
     */
    public function disableCache(): void
    {
        $this->config = new NullConfig();
    }

    /**
     * @return Cache
     * @throws InvalidArgumentException
     */
    public function getCache(): Cache
    {
        return self::createCache($this->config);
    }

    /**
     * @param ConfigInterface $config
     * @return Cache
     */
    public static function createCache(ConfigInterface $config): Cache
    {
        $cacheProviders = [
            new ArrayCache(),
        ];

        if ($config instanceof ApcuConfig) {
            $cacheProviders[] = new ApcuCache();
        } elseif ($config instanceof FileSystemConfig) {
            $cacheProviders[] = new FilesystemCache($config->getPath());
        }

        $cache = new ChainCache($cacheProviders);
        $cache->setNamespace('better-serializer');

        return $cache;
    }
}
