<?php
declare(strict_types = 1);

/**
 * @author  mfris
 */
namespace BetterSerializer\DataBind\MetaData;

/**
 * Class ClassMetadata
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 */
final class ClassMetadata implements ClassMetadataInterface
{

    /**
     * @var array<ClassMetadata>
     */
    private $annotations;

    /**
     * ClassMetadata constructor.
     * @param array $annotations
     */
    public function __construct(array $annotations)
    {
        $this->annotations = $annotations;
    }
}
