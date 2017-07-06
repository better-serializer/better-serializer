<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Model\ClassModel;

/**
 * Class ClassMetadata
 *
 * @author  mfris
 * @package BetterSerializer\DataBind\MetaData
 */
final class ClassMetaData implements ClassMetaDataInterface
{

    /**
     * @var string
     */
    private $className;

    /**
     * @var array<ClassMetadata>
     */
    private $annotations;

    /**
     * ClassMetadata constructor.
     *
     * @param string $className
     * @param array $annotations
     */
    public function __construct(string $className, array $annotations)
    {
        $this->className = $className;
        $this->annotations = $annotations;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return array
     */
    public function getAnnotations(): array
    {
        return $this->annotations;
    }
}
