<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Common;

/**
 * Interface CollectionExtensionInterface
 * @author mfris
 * @package BetterSerializer\Common
 */
interface CollectionExtensionInterface extends ExtensionInterface
{

    /**
     * return aan iterator wrapping the given collection
     *
     * @param Object $object
     * @return CollectionIteratorInterface
     */
    public function getIterator($object): CollectionIteratorInterface;

    /**
     * return an unwrapped iterator with a new instance of wrapped collection
     *
     * @return CollectionIteratorInterface
     */
    public function getNewIterator(): CollectionIteratorInterface;
}
