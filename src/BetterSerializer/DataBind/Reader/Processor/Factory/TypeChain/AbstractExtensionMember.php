<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain;

use RuntimeException;

/**
 *
 */
abstract class AbstractExtensionMember extends ChainMember implements ExtensibleChainMemberInterface
{

    /**
     * @var string[]
     */
    protected $customHandlerClasses;

    /**
     * @param string[] $customObjectClasses
     * @throws RuntimeException
     */
    public function __construct(array $customObjectClasses = [])
    {
        foreach ($customObjectClasses as $customObjectClass) {
            $this->addCustomHandlerClass($customObjectClass);
        }
    }

    /**
     * @param string $customHandlerClass
     * @throws RuntimeException
     */
    public function addCustomHandlerClass(string $customHandlerClass): void
    {
        if (!method_exists($customHandlerClass, 'getType')) {
            throw new RuntimeException(
                sprintf('Type handler %s is missing the getType method.', $customHandlerClass)
            );
        }

        $customType = call_user_func("{$customHandlerClass}::getType");

        if (isset($this->customHandlerClasses[$customType])) {
            throw new RuntimeException(sprintf('Handler for class %s is already registered.', $customType));
        }

        $this->customHandlerClasses[$customType] = $customHandlerClass;
    }
}
