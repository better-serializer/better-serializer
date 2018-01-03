<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\UseStatement\Factory;

use BetterSerializer\Flysystem\Plugin\FirstXLines;
use BetterSerializer\Reflection\UseStatement\CodeReader;
use BetterSerializer\Reflection\UseStatement\CodeReaderInterface;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use LogicException;
use ReflectionClass;

/**
 * Class CodeReaderFactory
 * @author mfris
 * @package BetterSerializer\Reflection\UseStatement\Factory
 */
final class CodeReaderFactory implements CodeReaderFactoryInterface
{

    /**
     * @param ReflectionClass $reflectionClass
     * @return CodeReaderInterface
     * @throws LogicException
     */
    public function newCodeReader(ReflectionClass $reflectionClass): CodeReaderInterface
    {
        $directory = dirname($reflectionClass->getFileName());
        $filesystem = new Filesystem(new Local($directory));
        $filesystem->addPlugin(new FirstXLines($reflectionClass->getStartLine()));

        return new CodeReader($filesystem);
    }
}
