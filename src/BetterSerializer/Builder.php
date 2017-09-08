<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer;

use Pimple\Container;
use Pimple\Exception\UnknownIdentifierException;
use RuntimeException;

/**
 * Class Builder
 * @author mfris
 * @package BetterSerializer
 */
final class Builder
{

    /**
     * @var Container
     */
    private $container;

    /**
     * Builder constructor.
     */
    public function __construct()
    {
        $this->initContainer();
    }

    /**
     * @return Serializer
     * @throws UnknownIdentifierException
     */
    public function createSerializer(): Serializer
    {
        return $this->container->offsetGet(Serializer::class);
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

        $this->container['Doctrine\Common\Cache\FilesystemCache|Directory'] = $directory;
    }

    /**
     *
     */
    private function initContainer(): void
    {
        $this->container = require dirname(__DIR__) . '/../config/di.pimple.php';
    }
}
