<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeReader;

use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\PropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\StringTypeParserInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use phpDocumentor\Reflection\DocBlockFactoryInterface;

/**
 *
 */
final class DocBlockPropertyTypeReader implements TypeReaderInterface
{

    /**
     * @var DocBlockFactoryInterface
     */
    private $docBlockFactory;

    /**
     * @var StringTypeParserInterface
     */
    private $stringTypeParser;

    /**
     * DocBlockPropertyTypeReader constructor.
     * @param DocBlockFactoryInterface $docBlockFactory
     * @param StringTypeParserInterface $stringTypeParser
     */
    public function __construct(DocBlockFactoryInterface $docBlockFactory, StringTypeParserInterface $stringTypeParser)
    {
        $this->docBlockFactory = $docBlockFactory;
        $this->stringTypeParser = $stringTypeParser;
    }

    /**
     * @param PropertyContextInterface $context
     * @return ContextStringFormTypeInterface|null
     * @throws \InvalidArgumentException
     */
    public function resolveType(PropertyContextInterface $context): ?ContextStringFormTypeInterface
    {
        $reflectionProperty = $context->getReflectionProperty();
        $docComment = $reflectionProperty->getDocComment();
        if ($docComment === '') {
            return null;
        }

        $docBlock = $this->docBlockFactory->create($docComment);
        $varTags = $docBlock->getTagsByName('var');

        if (empty($varTags)) {
            return null;
        }

        /** @var Var_[] $varTags */
        $type = $varTags[0]->getType();
        $stringType = (string) $type;

        return $this->stringTypeParser->parseWithParentContext($stringType, $context->getReflectionClass());
    }
}
