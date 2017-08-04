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
 * @Target("PROPERTY")
 * @Attributes({
 *   @Attribute(BetterSerializer\DataBind\MetaData\Annotations\Property::KEY_NAME, type="string"),
 *   @Attribute(BetterSerializer\DataBind\MetaData\Annotations\Property::KEY_TYPE, type="string"),
 * })
 */
final class Property implements PropertyInterface
{

    /**
     * @const string
     */
    const KEY_NAME = 'name';

    /**
     * @const string
     */
    const KEY_TYPE = 'type';

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $type = '';

    /**
     * Property constructor.
     * @param array $values
     * @throws Exception
     */
    public function __construct(array $values)
    {
        $this->setName($values);
        $this->setType($values);
    }

    /**
     * @param array $values
     * @throws Exception
     */
    private function setName(array $values): void
    {
        if (!array_key_exists(self::KEY_NAME, $values)) {
            return;
        }

        $name = trim((string) $values[self::KEY_NAME]);

        if ($name === '') {
            throw new Exception('Name property cannot be empty if set.');
        }

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param array $values
     * @throws Exception
     */
    private function setType(array $values): void
    {
        if (!array_key_exists(self::KEY_TYPE, $values)) {
            return;
        }

        $type = trim((string) $values[self::KEY_TYPE]);

        if ($type === '') {
            throw new Exception('Type property cannot be empty if set.');
        }

        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
