<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Common;

use ArrayAccess;
use Iterator;

/**
 * Interface CollectionIteratorInterface
 * @author mfris
 * @package BetterSerializer\Common
 */
interface CollectionIteratorInterface extends ArrayAccess, Iterator
{
}
