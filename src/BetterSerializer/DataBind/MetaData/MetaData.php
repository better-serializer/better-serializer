<?php
/**
 * @author  mfris
 */
declare(strict_types=1);

namespace BetterSerializer\DataBind\MetaData;

/**
 * Class MetaData
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 */
final class MetaData
{

    /**
     * @var ClassMetadataInterface
     */
    private $classMetadata;

    /**
     * @var PropertyMetadataInterface[]
     */
    private $propertyMetadata;

    /**
     * MetaData constructor.
     * @param ClassMetadataInterface $classMetadata
     * @param PropertyMetadataInterface[] $propertyMetadata
     */
    public function __construct(ClassMetadataInterface $classMetadata, array $propertyMetadata)
    {
        $this->classMetadata = $classMetadata;
        $this->propertyMetadata = $propertyMetadata;
    }
}
