<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData;

/**
 * Class MetaData
 *
 * @author  mfris
 * @package BetterSerializer\DataBind\MetaData
 */
final class MetaData implements MetaDataInterface
{

    /**
     * @var ClassMetadataInterface
     */
    private $classMetadata;

    /**
     * @var PropertyMetaDataInterface[]
     */
    private $propertiesMetadata;

    /**
     * MetaData constructor.
     *
     * @param ClassMetadataInterface      $classMetadata
     * @param PropertyMetaDataInterface[] $propertiesMetadata
     */
    public function __construct(ClassMetadataInterface $classMetadata, array $propertiesMetadata)
    {
        $this->classMetadata = $classMetadata;
        $this->propertiesMetadata = $propertiesMetadata;
    }

    /**
     * @return ClassMetadataInterface
     */
    public function getClassMetadata(): ClassMetadataInterface
    {
        return $this->classMetadata;
    }

    /**
     * @return PropertyMetaDataInterface[]
     */
    public function getPropertiesMetadata(): array
    {
        return $this->propertiesMetadata;
    }
}
