<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\UseStatement;

use BetterSerializer\Flysystem\Plugin\FirstXLines;
use League\Flysystem\FilesystemInterface;
use ReflectionClass;

/**
 *
 */
final class CodeReader implements CodeReaderInterface
{

    /**
     * @var FilesystemInterface|FirstXLines
     */
    private $fileReader;

    /**
     * CodeReader constructor.
     * @param FilesystemInterface $fileReader
     */
    public function __construct(FilesystemInterface $fileReader)
    {
        $this->fileReader = $fileReader;
    }

    /**
     * Read file source up to the line where our class is defined.
     *
     * @param ReflectionClass $reflectionClass
     * @return string
     */
    public function readUseStatementsSource(ReflectionClass $reflectionClass): string
    {
        $fileName = str_replace(dirname($reflectionClass->getFileName()), '', $reflectionClass->getFileName());

        return $this->fileReader->getFirstXLines($fileName);
    }
}
