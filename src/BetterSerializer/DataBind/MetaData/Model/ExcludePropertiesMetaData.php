<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;

/**
 * Class ExcludePropertiesMetaData
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Model
 */
final class ExcludePropertiesMetaData extends MetaDataDecorator
{

    /**
     * @var bool[]
     */
    private $excludeProperties;

    /**
     * @var PropertyMetaDataInterface[]
     */
    private $propertiesMetaData;

    /**
     * ExcludePropertiesMetaData constructor.
     * @param MetaDataInterface $metaData
     * @param string[] $excludeProperties
     */
    public function __construct(MetaDataInterface $metaData, array $excludeProperties)
    {
        parent::__construct($metaData);
        $this->excludeProperties = $this->processExcludedProperties($excludeProperties);
    }

    /**
     * @return array
     */
    public function getPropertiesMetadata(): array
    {
        if ($this->propertiesMetaData !== null) {
            return $this->propertiesMetaData;
        }

        $propertiesMetaData = parent::getPropertiesMetadata();
        $processed = [];

        foreach ($propertiesMetaData as $propertyName => $metaData) {
            if (!isset($this->excludeProperties[$propertyName])) {
                $processed[$propertyName] = $metaData;
            }
        }

        $this->propertiesMetaData = $processed;

        return $processed;
    }

    /**
     * @param string[] $excludedProperties
     * @return bool[]
     */
    private function processExcludedProperties(array $excludedProperties): array
    {
        $processed = [];

        foreach ($excludedProperties as $propertyName) {
            $processed[$propertyName] = true;
        }

        return $processed;
    }
}
