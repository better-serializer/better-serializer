<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Extractor\Property;

use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use ReflectionProperty as NativeReflectionProperty;

/**
 * Class ReflectionExtractor
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Extractor\Property
 */
final class ReflectionExtractor implements ExtractorInterface
{

    /**
     * @var ReflectionPropertyInterface
     */
    private $reflectionProperty;

    /**
     * @var NativeReflectionProperty
     */
    private $nativeReflectionProperty;

    /**
     * ReflectionExtractor constructor.
     * @param ReflectionPropertyInterface $reflectionProperty
     */
    public function __construct(ReflectionPropertyInterface $reflectionProperty)
    {
        $this->reflectionProperty = $reflectionProperty;
        $this->nativeReflectionProperty = $reflectionProperty->getNativeReflProperty();
    }

    /**
     * @param object $data
     * @return mixed
     */
    public function extract($data)
    {
        if ($data === null) {
            return null;
        }

        return $this->nativeReflectionProperty->getValue($data);
    }

    /**
     *
     */
    public function __wakeup()
    {
        $this->nativeReflectionProperty = $this->reflectionProperty->getNativeReflProperty();
    }
}
