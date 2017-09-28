<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use InvalidArgumentException;

/**
 * Class AnnotationReaderFactory
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
final class AnnotationReaderFactory
{

    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @return AnnotationReader
     * @throws InvalidArgumentException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function newAnnotationReader(): AnnotationReader
    {
        if ($this->annotationReader === null) {
            AnnotationRegistry::registerLoader('class_exists');
            $this->annotationReader = new AnnotationReader();
        }

        return $this->annotationReader;
    }
}
