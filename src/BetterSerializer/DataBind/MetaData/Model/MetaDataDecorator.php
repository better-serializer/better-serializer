<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;

/**
 * Class MetaDataDecorator
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Model
 */
abstract class MetaDataDecorator implements MetaDataInterface
{

    /**
     * @var MetaDataInterface
     */
    private $decorated;

    /**
     * MetaDataDecorator constructor.
     * @param MetaDataInterface $decorated
     */
    public function __construct(MetaDataInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * @return ClassMetaDataInterface
     */
    public function getClassMetadata(): ClassMetaDataInterface
    {
        return $this->getDedorated()->getClassMetadata();
    }

    /**
     * @return array
     */
    public function getPropertiesMetadata(): array
    {
        return $this->getDedorated()->getPropertiesMetadata();
    }

    /**
     * @return array
     */
    public function getConstructorParamsMetaData(): array
    {
        return $this->getDedorated()->getConstructorParamsMetaData();
    }

    /**
     * @return array
     */
    public function getPropertyWithConstructorParamTuples(): array
    {
        return $this->getDedorated()->getPropertyWithConstructorParamTuples();
    }

    /**
     * @return bool
     */
    public function isInstantiableByConstructor(): bool
    {
        return $this->getDedorated()->isInstantiableByConstructor();
    }

    /**
     * @return MetaDataInterface
     */
    protected function getDedorated(): MetaDataInterface
    {
        return $this->decorated;
    }
}
