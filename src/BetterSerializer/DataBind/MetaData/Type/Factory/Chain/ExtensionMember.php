<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\ExtensionClassType;
use BetterSerializer\DataBind\MetaData\Type\ExtensionType;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\Parameters;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\StringTypeParserInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use RuntimeException;

/**
 *
 */
final class ExtensionMember extends AbstractExtensionTypeMember
{

    /**
     * @var StringTypeParserInterface
     */
    private $stringTypeParser;

    /**
     * @var TypeFactoryInterface
     */
    private $typeFactory;

    /**
     * @param TypeFactoryInterface $typeFactory
     * @param StringTypeParserInterface $stringTypeParser
     * @param string[] $customObjectClasses
     * @throws RuntimeException
     */
    public function __construct(
        TypeFactoryInterface $typeFactory,
        StringTypeParserInterface $stringTypeParser,
        array $customObjectClasses = []
    ) {
        $this->typeFactory = $typeFactory;
        $this->stringTypeParser = $stringTypeParser;
        parent::__construct($customObjectClasses);
    }

    /**
     * @param ContextStringFormTypeInterface $stringFormType
     * @return bool
     */
    protected function isProcessable(ContextStringFormTypeInterface $stringFormType): bool
    {
        return isset($this->customTypes[$stringFormType->getStringType()]);
    }

    /**
     * @param ContextStringFormTypeInterface $stringFormType
     * @return TypeInterface
     * @throws RuntimeException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function createType(ContextStringFormTypeInterface $stringFormType): TypeInterface
    {
        $parameters = $stringFormType->getParameters();

        if (!$parameters) {
            $parameters = new Parameters([]);
        }

        $currentType = $stringFormType->getStringType();
        $typeClass = $stringFormType->getTypeClass();

        if ($typeClass === TypeClassEnum::CLASS_TYPE() || $typeClass === TypeClassEnum::INTERFACE_TYPE()) {
            return new ExtensionClassType($currentType, $parameters);
        }

        $replacedType = null;
        $replacedTypeString = call_user_func("{$this->customTypes[$currentType]}::getReplacedType");

        if ($replacedTypeString) {
            $replacedStringFormType = $this->stringTypeParser->parseSimple($replacedTypeString);
            $replacedType = $this->typeFactory->getType($replacedStringFormType);
        }

        return new ExtensionType($currentType, $parameters, $replacedType);
    }
}
