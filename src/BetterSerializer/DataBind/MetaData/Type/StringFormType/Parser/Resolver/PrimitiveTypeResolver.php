<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ResultInterface as FormatResultInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;

/**
 *
 */
final class PrimitiveTypeResolver implements ResolverInterface
{

    /**
     * @var string[]
     */
    private static $primitiveTypes = [
        TypeEnum::BOOLEAN => TypeEnum::BOOLEAN,
        TypeEnum::ARRAY => TypeEnum::ARRAY,
        TypeEnum::FLOAT => TypeEnum::FLOAT,
        TypeEnum::INTEGER => TypeEnum::INTEGER,
        TypeEnum::STRING => TypeEnum::STRING,
    ];

    /**
     * @param FormatResultInterface $formatResult
     * @param ContextInterface $context
     * @return ResultInterface|null
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function resolve(
        FormatResultInterface $formatResult,
        ContextInterface $context
    ): ?ResultInterface {
        $stringType = $formatResult->getType();

        if (!isset(self::$primitiveTypes[$stringType])) {
            return null;
        }

        return new Result($stringType, TypeClassEnum::PRIMITIVE_TYPE());
    }
}
