<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Extension\Registry;

use BetterSerializer\Extension\Registry\Registrator\CollectingRegistratorInterface;
use RuntimeException;

/**
 *
 */
final class CollectingRegistry extends AbstractRegistry implements CollectingRegistryInterface
{

    /**
     * @return CollectionInterface[]
     * @throws RuntimeException
     */
    public function getTypeCollections(): array
    {
        $collections = [];

        foreach ($this->registrators as $registrator) {
            if (!$registrator instanceof CollectingRegistratorInterface) {
                throw new RuntimeException('Registrator is not a CollectingRegistrator.');
            }
            $collections[$registrator->getExtTypeInterface()] = $registrator->getCollection();
        }

        return $collections;
    }
}
