<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use RuntimeException;

/**
 *
 */
abstract class AbstractExtensionTypeMember extends ChainMember implements ExtensibleChainMemberInterface
{

    /**
     * @var array
     */
    protected $customTypes;

    /**
     * @param string[] $customObjectClasses
     * @throws RuntimeException
     */
    public function __construct(array $customObjectClasses = [])
    {
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
