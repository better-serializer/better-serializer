<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Dto;

use Doctrine\Common\Collections\ArrayCollection;
use stdClass;

/**
 *
 */
final class DerivedArrayCollection extends ArrayCollection
{

    /**
     * @var stdClass
     */
    private $foo;

    /**
     * @param stdClass $foo
     * @param array $elements
     */
    public function __construct(stdClass $foo, array $elements = [])
    {
        $this->foo = $foo;

        parent::__construct($elements);
    }

    /**
     * @param array $elements
     * @return DerivedArrayCollection
     */
    protected function createFrom(array $elements) : self
    {
        return new static($this->foo, $elements);
    }
}
