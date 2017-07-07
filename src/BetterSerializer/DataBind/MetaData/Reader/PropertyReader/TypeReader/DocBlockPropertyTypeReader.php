<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeReader;

use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\PropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\StringTypedPropertyContext;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\StringTypedPropertyContextInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use RuntimeException;

/**
 * Class DocBlockPropertyTypeReader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
final class DocBlockPropertyTypeReader implements TypeReaderInterface
{

    /**
     * @var DocBlockFactoryInterface
     */
    private $docBlockFactory;

    /**
     * DocBlockPropertyTypeReader constructor.
     * @param DocBlockFactoryInterface $docBlockFactory
     */
    public function __construct(DocBlockFactoryInterface $docBlockFactory)
    {
        $this->docBlockFactory = $docBlockFactory;
    }

    /**
     * @param PropertyContextInterface $context
     * @return StringTypedPropertyContextInterface|null
     * @throws RuntimeException
     */
    public function resolveType(PropertyContextInterface $context): ?StringTypedPropertyContextInterface
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

        return new StringTypedPropertyContext($context, (string) $type);
    }
}
