<?php
declare(strict_types = 1);

/**
 * @author  mfris
 */
namespace BetterSerializer\DataBind\MetaData\Annotations;

/**
 * Class RootName
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Annotations
 * @Annotation
 * @Target("CLASS")
 * @Attributes({
 *   @Attribute(BetterSerializer\DataBind\MetaData\Annotations\RootName::KEY_VALUE, type="string", required=true),
 * })
 */
final class RootName implements AnnotationInterface
{

    /**
     * @const string
     */
    public const KEY_VALUE = 'value';

    /**
     * @var string
     */
    private $value;

    /**
     * RootName constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->value = $values[self::KEY_VALUE];
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
