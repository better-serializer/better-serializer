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
     * @param mixed $collection
     * @return bool
     */
    public function isEmpty($collection): bool;

    /**
     * return aan iterator wrapping the given collection
     *
     * @param Object $collection
     * @return CollectionAdapterInterface
     */
    public function getAdapter($collection): CollectionAdapterInterface;

    /**
     * return an unwrapped iterator with a new instance of wrapped collection
     *
     * @return CollectionAdapterInterface
     */
    public function getNewAdapter(): CollectionAdapterInterface;
}
