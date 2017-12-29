<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\Parameters\ParserInterface;
use RuntimeException;

/**
 *
 */
abstract class AbstractExtensionTypeMember extends ChainMember implements ExtensibleChainMemberInterface
{

    /**
     * @var ParserInterface
     */
    protected $parametersParser;

    /**
     * @var array
     */
    protected $customTypes;

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

        if (isset($this->customTypes[$customType])) {
            throw new RuntimeException(sprintf('Handler for class %s is already registered.', $customType));
        }

        $this->customTypes[$customType] = $customObjectHandlerClass;
    }
}
