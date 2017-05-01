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
final class ObjectType extends AbstractType
{

    /**
     * StringDataType constructor.
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct()
    {
        if ($this->type === null) {
            $this->type = TypeEnum::OBJECT();
        }
    }
}
