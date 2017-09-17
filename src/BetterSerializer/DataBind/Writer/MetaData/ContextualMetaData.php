<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\MetaData;

use BetterSerializer\DataBind\MetaData\Model\AbstractMetaDataDecorator;
use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;

/**
 * Class ContextualMetaData
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\MetaData
 */
final class ContextualMetaData extends AbstractMetaDataDecorator
{

    /**
     * @var PropertyMetaDataInterface[]
     */
    private $propertiesMetaData;

    /**
     * ContextualMetaData constructor.
     * @param MetaDataInterface $decorated
     * @param SerializationContextInterface $context
     */
    public function __construct(MetaDataInterface $decorated, SerializationContextInterface $context)
    {
        parent::__construct($decorated);
        $this->propertiesMetaData = $this->filterPropertiesByGroups($context->getGroups());
    }

    /**
     * @return PropertyMetaDataInterface[]
     */
    public function getPropertiesMetadata(): array
    {
        return $this->propertiesMetaData;
    }

    /**
     * @param string[] $filteringGroups
     * @return PropertyMetaDataInterface[]
     */
    private function filterPropertiesByGroups(array $filteringGroups): array
    {
        $properties = [];

        foreach (parent::getPropertiesMetadata() as $name => $metaData) {
            foreach ($metaData->getGroups() as $mGroup) {
                if (in_array($mGroup, $filteringGroups, true)) {
                    $properties[$name] = $metaData;

                    continue;
                }
            }
        }

        return $properties;
    }
}
