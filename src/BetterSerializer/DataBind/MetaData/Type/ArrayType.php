<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

/**
 * Class String
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
final class ArrayType extends AbstractCollectionType
{

    /**
     * StringDataType constructor.
     * @param TypeInterface $nestedType
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct(TypeInterface $nestedType)
    {
        $this->type = TypeEnum::ARRAY();
        parent::__construct($nestedType);
    }
}
