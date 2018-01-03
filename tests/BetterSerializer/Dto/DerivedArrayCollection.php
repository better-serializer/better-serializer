<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Dto;

use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 */
final class DerivedArrayCollection extends ArrayCollection
{
    private $foo;

    public function __construct(\stdClass $foo, array $elements = [])
    {
        $this->foo = $foo;

        parent::__construct($elements);
    }

    protected function createFrom(array $elements) : self
    {
        return new static($this->foo, $elements);
    }
}
