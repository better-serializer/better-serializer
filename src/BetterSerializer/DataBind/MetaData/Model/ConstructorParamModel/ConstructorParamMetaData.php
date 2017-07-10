<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class ConstructorParam
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel
 */
final class ConstructorParamMetaData implements ConstructorParamMetaDataInterface
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var TypeInterface
     */
    private $type;

    /**
     * @var string
     */
    private $propertyName;

    /**
     * ConstructorParam constructor.
     * @param string $name
     * @param TypeInterface $type
     * @param string $propertyName
     */
    public function __construct($name, TypeInterface $type, $propertyName = '')
    {
        $this->setName($name);
        $this->type = $type;
        $this->setPropertyName($propertyName);
    }

    /**
     * @param string $name
     * @return void
     */
    private function setName(string $name): void
    {
        $this->name = trim($name);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return $this->type;
    }

    /**
     * @param string $propertyName
     * @return void
     */
    private function setPropertyName(string $propertyName): void
    {
        $propertyName = trim($propertyName);
        $this->propertyName = $propertyName ?: $this->getName();
    }

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }
}
