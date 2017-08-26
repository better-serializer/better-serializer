<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\UseStatement\Factory;

use BetterSerializer\Flysystem\Plugin\FirstXLines;
use BetterSerializer\Reflection\UseStatement\CodeReader;
use BetterSerializer\Reflection\UseStatement\CodeReaderInterface;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use LogicException;

/**
 * Class CodeReaderFactory
 * @author mfris
 * @package BetterSerializer\Reflection\UseStatement\Factory
 */
final class CodeReaderFactory implements CodeReaderFactoryInterface
{

    /**
     * @param int $classOffset
     * @return CodeReaderInterface
     * @throws LogicException
     */
    public function newCodeReader(int $classOffset): CodeReaderInterface
    {
        $filesystem = new Filesystem(new Local('/'));
        $filesystem->addPlugin(new FirstXLines($classOffset));

        return new CodeReader($filesystem);
    }
}
