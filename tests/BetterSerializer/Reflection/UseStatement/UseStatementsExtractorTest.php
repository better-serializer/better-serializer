<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\UseStatement;

use BetterSerializer\Reflection\UseStatement\Factory\CodeReaderFactoryInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Class UseStatementsExtractorTest
 * @author mfris
 * @package BetterSerializer\Reflection\UseStatement
 */
class UseStatementsExtractorTest extends TestCase
{

    /**
     *
     */
    public function testNewUseStatements(): void
    {
        $startLine = 4;
        $source = '<?php';
        $reflectionClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflectionClass->method('getStartLine')
            ->willReturn($startLine);

        $codeReader = $this->createMock(CodeReaderInterface::class);
        $codeReader->method('readUseStatementsSource')
            ->with($reflectionClass)
            ->willReturn($source);

        $codeReaderFactory = $this->createMock(CodeReaderFactoryInterface::class);
        $codeReaderFactory->method('newCodeReader')
            ->with($startLine)
            ->willReturn($codeReader);

        $parser = $this->createMock(ParserInterface::class);
        $parser->method('parseUseStatements')
            ->with($source)
            ->willReturn([]);

        /* @var $reflectionClass ReflectionClass */
        $factory = new UseStatementsExtractor($codeReaderFactory, $parser);
        $useStatements = $factory->newUseStatements($reflectionClass);

        self::assertInstanceOf(UseStatementsInterface::class, $useStatements);

        $reflectionClass->method('isUserDefined')
            ->willReturn(true);

        $useStatements = $factory->newUseStatements($reflectionClass);

        self::assertInstanceOf(UseStatementsInterface::class, $useStatements);
    }
}
