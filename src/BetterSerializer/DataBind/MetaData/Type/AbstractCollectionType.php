<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

/**
 * Class String
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
abstract class AbstractCollectionType extends AbstractType implements ComplexTypeInterface
{

    /**
     * @var TypeInterface
     */
    private $nestedType;

    /**
     * StringDataType constructor.
     * @param TypeInterface $nestedType
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct(TypeInterface $nestedType)
    {
        parent::__construct();
        $this->nestedType = $nestedType;
    }

    /**
     * @return TypeInterface
     */
    public function getNestedType(): TypeInterface
    {
        return $this->nestedType;
    }
}
