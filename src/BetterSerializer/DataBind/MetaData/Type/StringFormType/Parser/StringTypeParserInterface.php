<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\Reflection\ReflectionClassInterface;

/**
 *
 */
interface StringTypeParserInterface
{
    /**
     * @param string $stringType
     * @param ReflectionClassInterface $contextReflClass
     * @return ContextStringFormTypeInterface
     */
    public function parseWithParentContext(
        string $stringType,
        ReflectionClassInterface $contextReflClass
    ): ContextStringFormTypeInterface;

    /**
     * @param string $fqStringType
     * @return ContextStringFormTypeInterface
     */
    public function parseSimple(string $fqStringType): ContextStringFormTypeInterface;
}
