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
     * @var string
     */
    private $className;

    /**
     * StringDataType constructor.
     * @param string $className
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct(string $className)
    {
        $this->type = TypeEnum::OBJECT();
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }
}
