<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\UseStatement;

use BetterSerializer\Reflection\UseStatement\Factory\CodeReaderFactoryInterface;
use ReflectionClass;
use LogicException;
use RuntimeException;

/**
 * Class UseStatementsExtractor
 * @author mfris
 * @package BetterSerializer\Reflection\UseStatement
 */
final class UseStatementsExtractor implements UseStatementsExtractorInterface
{

    /**
     * @var CodeReaderFactoryInterface
     */
    private $codeReaderFactory;

    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * UseStatementsFactory constructor.
     * @param CodeReaderFactoryInterface $codeReaderFactory
     * @param ParserInterface $parser
     */
    public function __construct(CodeReaderFactoryInterface $codeReaderFactory, ParserInterface $parser)
    {
        $this->codeReaderFactory = $codeReaderFactory;
        $this->parser = $parser;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return UseStatementsInterface
     * @throws LogicException
     * @throws RuntimeException
     */
    public function newUseStatements(ReflectionClass $reflectionClass): UseStatementsInterface
    {
        if (!$reflectionClass->isUserDefined()) {
            return new UseStatements([]);
        }

        $codeReader = $this->codeReaderFactory->newCodeReader($reflectionClass);
        $usedStatementsSrc = $codeReader->readUseStatementsSource($reflectionClass);
        $statementsArray = $this->parser->parseUseStatements($usedStatementsSrc);

        return new UseStatements($statementsArray);
    }
}
