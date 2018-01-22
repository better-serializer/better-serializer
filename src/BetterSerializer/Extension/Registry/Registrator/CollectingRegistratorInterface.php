<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Extension\Registry\Registrator;

use BetterSerializer\Extension\Registry\CollectionInterface;

/**
 *
 */
interface CollectingRegistratorInterface extends RegistratorInterface
{

    /**
     * @return CollectionInterface
     */
    public function getCollection(): CollectionInterface;
}
