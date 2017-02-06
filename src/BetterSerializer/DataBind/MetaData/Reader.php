<?php
/**
 * @author  mfris
 */
namespace BetterSerializer\DataBind\MetaData;

use phpDocumentor\Reflection\DocBlockFactoryInterface;

/**
 * Class Reader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 */
final class Reader
{

    /**
     * @var DocBlockFactoryInterface
     */
    private $docBlockFactory;

    /**
     * Reader constructor.
     * @param DocBlockFactoryInterface $docBlockFactory
     */
    public function __construct(DocBlockFactoryInterface $docBlockFactory)
    {
        $this->docBlockFactory = $docBlockFactory;
    }

    /**
     * @param string $className
     * @return MetaData
     */
    public function read(string $className): MetaData
    {
        return new MetaData();
    }
}
