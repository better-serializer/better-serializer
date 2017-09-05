<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Extractor\Property;

use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use ReflectionProperty;

/**
 * Class ReflectionExtractor
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Extractor\Property
 */
final class ReflectionExtractor implements ExtractorInterface
{

    /**
     * @var ReflectionProperty
     */
    private $reflectionProperty;

    /**
     * ReflectionExtractor constructor.
     * @param ReflectionPropertyInterface $reflectionProperty
     */
    public function __construct(ReflectionPropertyInterface $reflectionProperty)
    {
        $this->reflectionProperty = $reflectionProperty->getNativeReflProperty();
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

        return $this->reflectionProperty->getValue($data);
    }
}
