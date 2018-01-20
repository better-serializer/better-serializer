<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Extension\Registry;

/**
 *
 */
final class Collection implements CollectionInterface
{

    /**
     * @var string[]
     */
    private $registeredTypes = [];

    /**
     * @param string $extensionClass
     */
    public function registerExtension(string $extensionClass): void
    {
        $extTypeString = call_user_func("{$extensionClass}::getType");
        $this->registeredTypes[$extTypeString] = $extensionClass;
    }

    /**
     * @param string $typeString
     * @return bool
     */
    public function hasType(string $typeString): bool
    {
        return isset($this->registeredTypes[$typeString]);
    }
}
