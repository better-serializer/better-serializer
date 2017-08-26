<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\UseStatement\Factory;

use BetterSerializer\Reflection\UseStatement\CodeReader;
use PHPUnit\Framework\TestCase;

/**
 * Class CodeReaderFactoryTest
 * @author mfris
 * @package BetterSerializer\Reflection\UseStatement\Factory
 */
class CodeReaderFactoryTest extends TestCase
{

    /**
     *
     */
    public function testNewCodeReader(): void
    {
        $factory = new CodeReaderFactory();
        $codeReader = $factory->newCodeReader(3);

        self::assertInstanceOf(CodeReader::class, $codeReader);
    }
}
