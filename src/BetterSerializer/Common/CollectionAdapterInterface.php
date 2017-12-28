<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Common;

use ArrayAccess;
use IteratorAggregate;

/**
 * Interface CollectionIteratorInterface
 * @author mfris
 * @package BetterSerializer\Common
 */
interface CollectionAdapterInterface extends IteratorAggregate, ArrayAccess
{

    /**
     * @return mixed
     */
    public function getCollection();
}
