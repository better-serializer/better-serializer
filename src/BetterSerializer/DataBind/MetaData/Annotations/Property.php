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
 *   @Attribute(BetterSerializer\DataBind\MetaData\Annotations\Property::KEY_TYPE, type="string", required=true),
 * })
 */
final class Property implements AnnotationInterface
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
    private $name;

    /**
     * @var string
     */
    private $type;

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
    private function setName(array $values)
    {
        if (array_key_exists(self::KEY_NAME, $values)) {
            $name = trim((string) $values[self::KEY_NAME]);

            if ($name === '') {
                throw new Exception('Name property cannot be empty.');
            }

            $this->name = $name;
        }
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
     */
    private function setType(array $values)
    {
        $type = trim((string) $values[self::KEY_TYPE]);
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
