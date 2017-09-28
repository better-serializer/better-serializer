<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
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
final class RootName extends AbstractAnnotation
{

    /**
     * @const string
     */
    public const ANNOTATION_NAME = 'RootName';

    /**
     * @const string
     */
    const KEY_VALUE = 'value';

    /**
     * @var string
     */
    private $value;

    /**
     * RootName constructor.
     * @param array $values
     * @throws Exception
     */
    public function __construct(array $values)
    {
        $this->setValue($values);
    }

    /**
     * @param array $values
     * @return void
     * @throws Exception
     */
    private function setValue(array $values): void
    {
        if (!array_key_exists(self::KEY_VALUE, $values)) {
            throw new Exception('Value missing.');
        }

        $value = trim((string) $values[self::KEY_VALUE]);

        if ($value === '') {
            throw new Exception('Value property cannot be empty if set.');
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
