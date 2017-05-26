<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use phpDocumentor\Reflection\Types\Context;
use ReflectionProperty;
use RuntimeException;

/**
 * Class DocBlockPropertyTypeReader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
final class DocBlockPropertyTypeReader implements DocBlockPropertyTypeReaderInterface
{

    /**
     * @var DocBlockFactoryInterface
     */
    private $docBlockFactory;

    /**
     * @var TypeFactoryInterface
     */
    private $typeFactory;

    /**
     * DocBlockPropertyTypeReader constructor.
     * @param DocBlockFactoryInterface $docBlockFactory
     * @param TypeFactoryInterface $typeFactory
     */
    public function __construct(DocBlockFactoryInterface $docBlockFactory, TypeFactoryInterface $typeFactory)
    {
        $this->docBlockFactory = $docBlockFactory;
        $this->typeFactory = $typeFactory;
    }

    /**
     * @param ReflectionProperty $reflectionProperty
     * @return TypeInterface
     * @throws RuntimeException
     */
    public function getType(ReflectionProperty $reflectionProperty): TypeInterface
    {
        $docComment = $reflectionProperty->getDocComment();
        if ($docComment === '') {
            throw new RuntimeException(
                sprintf('You need to add a docblock to property "%s"', $reflectionProperty->getName())
            );
        }

        $namespace = $reflectionProperty->getDeclaringClass()->getNamespaceName();
        $context = new Context($namespace);
        $docBlock = $this->docBlockFactory->create($docComment, $context);
        $varTags = $docBlock->getTagsByName('var');

        if (empty($varTags)) {
            throw new RuntimeException(
                sprintf(
                    'You need to add an @var annotation to property "%s"',
                    $reflectionProperty->getName()
                )
            );
        }

        /** @var Var_[] $varTags */
        $type = $varTags[0]->getType();

        return $this->typeFactory->getType((string) $type);
    }
}
