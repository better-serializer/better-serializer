<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Extractor\Property;

use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
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
     * @param ReflectionProperty $reflectionProperty
     */
    public function __construct(ReflectionProperty $reflectionProperty)
    {
        $this->reflectionProperty = $reflectionProperty;
    }

    /**
     * @param object $data
     * @return mixed
     */
    public function extract($data)
    {
        return $this->reflectionProperty->getValue($data);
    }
}
