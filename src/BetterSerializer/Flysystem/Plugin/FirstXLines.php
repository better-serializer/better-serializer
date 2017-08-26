<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Flysystem\Plugin;

use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\PluginInterface;
use LogicException;
use RuntimeException;

/**
 * Class FirstXBytes
 * @author mfris
 * @package BetterSerializer\Flysystem\Plugin
 * @method string getFirstXLines(string $path)
 */
final class FirstXLines implements PluginInterface
{

    /**
     * @var int
     */
    private $lines = 1;

    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @param int $lines
     * @throws LogicException
     */
    public function __construct(int $lines)
    {
        if ($lines < 0) {
            throw new LogicException('$bytes must be greater than zero.');
        }

        $this->lines = $lines;
    }

    /**
     * @param FilesystemInterface $filesystem
     */
    public function setFilesystem(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return 'getFirstXLines';
    }

    /**
     * @param string|null $path
     * @return string
     * @throws FileNotFoundException
     * @throws RuntimeException
     */
    public function handle(string $path = null)
    {
        if (!$path) {
            throw new RuntimeException('Path missing.');
        }

        $stream = $this->filesystem->readStream($path);
        $content = '';

        for ($i = 0; $i < $this->lines; $i++) {
            $content .= fgets($stream);
        }

        fclose($stream);

        return $content;
    }
}
