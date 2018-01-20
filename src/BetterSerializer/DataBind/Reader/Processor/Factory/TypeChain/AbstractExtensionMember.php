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
    protected $extensionClasses;

    /**
     * @param string[] $extensionClasses
     * @throws RuntimeException
     */
    public function __construct(array $extensionClasses = [])
    {
        foreach ($extensionClasses as $extensionClass) {
            $this->addExtensionClass($extensionClass);
        }
    }

    /**
     * @param string $extensionClass
     * @throws RuntimeException
     */
    public function addExtensionClass(string $extensionClass): void
    {
        if (!method_exists($extensionClass, 'getType')) {
            throw new RuntimeException(
                sprintf('Type handler %s is missing the getType method.', $extensionClass)
            );
        }

        $customType = call_user_func("{$extensionClass}::getType");

        if (isset($this->extensionClasses[$customType])) {
            throw new RuntimeException(sprintf('Handler for class %s is already registered.', $customType));
        }

        $this->extensionClasses[$customType] = $extensionClass;
    }
}
