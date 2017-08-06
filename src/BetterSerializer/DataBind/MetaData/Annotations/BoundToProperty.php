<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Annotations;

/**
 * Class Property
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Annotations
 * @Annotation
 * @Target("METHOD")
 * @Attributes({
 *   @Attribute(BetterSerializer\DataBind\MetaData\Annotations\BoundToProperty::KEY_PROPERTY_NAME, type="string"),
 *   @Attribute(BetterSerializer\DataBind\MetaData\Annotations\BoundToProperty::KEY_ARGUMENT_NAME, type="string"),
 * })
 */
final class BoundToProperty implements BoundToPropertyInterface
{

    /**
     * @const string
     */
    const KEY_PROPERTY_NAME = 'propertyName';

    /**
     * @const string
     */
    const KEY_ARGUMENT_NAME = 'argumentName';

    /**
     * @var string
     * @Required
     */
    private $propertyName = '';

    /**
     * @var string
     * @Required
     */
    private $argumentName = '';

    /**
     * Property constructor.
     * @param array $values
     * @throws Exception
     */
    public function __construct(array $values)
    {
        $this->setPropertyName($values);
        $this->setArgumentName($values);
    }

    /**
     * @param array $values
     * @throws Exception
     */
    private function setPropertyName(array $values): void
    {
        if (!array_key_exists(self::KEY_PROPERTY_NAME, $values)) {
            throw new Exception('Property name missing.');
        }

        $name = trim((string) $values[self::KEY_PROPERTY_NAME]);

        if ($name === '') {
            throw new Exception('Property name cannot be empty.');
        }

        $this->propertyName = $name;
    }

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * @param array $values
     * @throws Exception
     */
    private function setArgumentName(array $values): void
    {
        if (!array_key_exists(self::KEY_ARGUMENT_NAME, $values)) {
            throw new Exception('Argument name missing.');
        }

        $type = trim((string) $values[self::KEY_ARGUMENT_NAME]);

        if ($type === '') {
            throw new Exception('Argument name cannot be empty.');
        }

        $this->argumentName = $type;
    }

    /**
     * @return string
     */
    public function getArgumentName(): string
    {
        return $this->argumentName;
    }
}
