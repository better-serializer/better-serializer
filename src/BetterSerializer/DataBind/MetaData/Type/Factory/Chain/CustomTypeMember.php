<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\CustomObjectType;
use BetterSerializer\DataBind\MetaData\Type\CustomType;
use BetterSerializer\DataBind\MetaData\Type\Parameters\ParserInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use RuntimeException;

/**
 *
 */
final class CustomTypeMember extends ChainMember implements ExtensibleChainMemberInterface
{

    /**
     * @var ParserInterface
     */
    private $parametersParser;

    /**
     * @var array
     */
    private $customObjectClasses;

    /**
     * @var string
     */
    private $currentType;

    /**
     * @param ParserInterface $parametersParser
     * @param string[] $customObjectClasses
     * @throws RuntimeException
     */
    public function __construct(ParserInterface $parametersParser, array $customObjectClasses = [])
    {
        $this->parametersParser = $parametersParser;

        foreach ($customObjectClasses as $customObjectClass) {
            $this->addCustomTypeHandlerClass($customObjectClass);
        }
    }

    /**
     * @param string $customObjectHandlerClass
     * @throws RuntimeException
     */
    public function addCustomTypeHandlerClass(string $customObjectHandlerClass): void
    {
        if (!method_exists($customObjectHandlerClass, 'getType')) {
            throw new RuntimeException(
                sprintf('Type handler %s is missing the getType method.', $customObjectHandlerClass)
            );
        }

        $customType = call_user_func("{$customObjectHandlerClass}::getType");

        if (isset($this->customObjectClasses[$customType])) {
            throw new RuntimeException(sprintf('Handler for class %s is already registered.', $customType));
        }

        $this->customObjectClasses[$customType] = $customType;
    }

    /**
     * @param StringFormTypeInterface $stringFormType
     * @return bool
     */
    protected function isProcessable(StringFormTypeInterface $stringFormType): bool
    {
        if (empty($this->customObjectClasses)) {
            return false;
        }

        if (!preg_match("/^(?P<type>\\\?[A-Za-z][a-zA-Z0-9_\\\]*)/", $stringFormType->getStringType(), $matches)) {
            return false;
        }

        $this->currentType = $matches['type'];

        if (!isset($this->customObjectClasses[$this->currentType])) {
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

        if ($stringFormType->isClass()) {
            return new CustomObjectType($this->currentType, $parameters);
        }

        return new CustomType($this->currentType, $parameters);
    }
}
