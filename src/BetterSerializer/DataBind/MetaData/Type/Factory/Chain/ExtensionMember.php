<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\ExtensionObjectType;
use BetterSerializer\DataBind\MetaData\Type\ExtensionType;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\Parameters\ParserInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\FqdnStringFormType;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use RuntimeException;

/**
 *
 */
final class ExtensionMember extends AbstractExtensionTypeMember
{

    /**
     * @var TypeFactoryInterface
     */
    private $typeFactory;

    /**
     * @var string
     */
    private $currentType;

    /**
     * @param TypeFactoryInterface $typeFactory
     * @param ParserInterface $parametersParser
     * @param string[] $customObjectClasses
     * @throws RuntimeException
     */
    public function __construct(
        TypeFactoryInterface $typeFactory,
        ParserInterface $parametersParser,
        array $customObjectClasses = []
    ) {
        $this->typeFactory = $typeFactory;
        parent::__construct($parametersParser, $customObjectClasses);
    }

    /**
     * @param StringFormTypeInterface $stringFormType
     * @return bool
     */
    protected function isProcessable(StringFormTypeInterface $stringFormType): bool
    {
        if (empty($this->customTypes)) {
            return false;
        }

        if (!preg_match("/^(?P<type>\\\?[A-Za-z][a-zA-Z0-9_\\\]*)/", $stringFormType->getStringType(), $matches)) {
            return false;
        }

        $this->currentType = $matches['type'];

        if (!isset($this->customTypes[$this->currentType])) {
            return false;
        }

        return true;
    }

    /**
     * @param StringFormTypeInterface $stringFormType
     * @return TypeInterface
     */
    protected function createType(StringFormTypeInterface $stringFormType): TypeInterface
    {
        $parameters = $this->parametersParser->parseParameters($stringFormType);

        if ($stringFormType->isClassOrInterface()) {
            return new ExtensionObjectType($this->currentType, $parameters);
        }

        $replacedType = null;
        $replacedTypeString = call_user_func("{$this->customTypes[$this->currentType]}::getReplacedType");

        if ($replacedTypeString) {
            $replacedType = $this->typeFactory->getType(new FqdnStringFormType($replacedTypeString));
        }

        return new ExtensionType($this->currentType, $parameters, $replacedType);
    }
}
