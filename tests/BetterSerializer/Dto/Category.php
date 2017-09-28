<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\Dto;

use JMS\Serializer\Annotation as JmsSerializer;

/**
 * Class Category
 * @author mfris
 * @package BetterSerializer\Dto
 */
class Category
{
    use TimeStampable;

    /**
     * @JmsSerializer\Type("integer")
     *
     * @var int
     */
    private $id;

    /**
     * @JmsSerializer\Type("BetterSerializer\Dto\Category")
     *
     * @var Category|null
     */
    private $parent;

    /**
     * @JmsSerializer\Type("array<integer,BetterSerializer\Dto\Category>")
     *
     * @var Category[]
     */
    private $children = [];

    /**
     * @param int           $id
     * @param Category|null $parent
     * @param Category[]    $children
     */
    public function __construct(int $id, Category $parent = null, array $children = [])
    {
        $this->setId($id);
        $this->setParent($parent);
        $this->setChildren($children);
        $this->initializeTimeStampable();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Category|null
     */
    public function getParent(): ?Category
    {
        return $this->parent;
    }

    /**
     * @param Category|null $parent
     */
    public function setParent(Category $parent = null): void
    {
        $this->parent = $parent;
    }

    /**
     * @return Category[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param Category[] $children
     */
    public function setChildren(array $children): void
    {
        $this->children = $children;
    }

    /**
     * @param Category $child
     */
    public function addChild(Category $child): void
    {
        $this->children[] = $child;
    }
}
