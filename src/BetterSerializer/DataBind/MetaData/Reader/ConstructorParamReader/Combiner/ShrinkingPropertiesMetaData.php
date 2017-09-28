<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use RuntimeException;

/**
 * Class ShrinkingPropertiesMetaData
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner
 */
final class ShrinkingPropertiesMetaData implements ShrinkingPropertiesMetaDataInterface
{

    /**
     * @var PropertyMetaDataInterface[]
     */
    private $propertiesMetaData;

    /**
     * ShrinkingPropertiesMetaData constructor.
     * @param PropertyMetaDataInterface[] $propertiesMetaData
     */
    public function __construct(array $propertiesMetaData)
    {
        $this->propertiesMetaData = $propertiesMetaData;
    }

    /**
     * @param string $propertyName
     * @return PropertyMetaDataInterface
     * @throws RuntimeException
     */
    public function shrinkBy(string $propertyName): PropertyMetaDataInterface
    {
        if (!isset($this->propertiesMetaData[$propertyName])) {
            throw new RuntimeException(sprintf("MetaData missing for '%s'.", $propertyName));
        }

        $toReturn = $this->propertiesMetaData[$propertyName];
        unset($this->propertiesMetaData[$propertyName]);

        return $toReturn;
    }

    /**
     * @param string $propertyName
     * @return bool
     */
    public function hasProperty(string $propertyName): bool
    {
        return isset($this->propertiesMetaData[$propertyName]);
    }
}
