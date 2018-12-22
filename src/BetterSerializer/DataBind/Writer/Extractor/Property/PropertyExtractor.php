<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Extractor\Property;

use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use Closure;

/**
 *
 */
final class PropertyExtractor implements ExtractorInterface
{

    /**
     * @var string
     */
    private $propertyName;

    /**
     * @var string
     */
    private $className;

    /**
     * @var Closure
     */
    private $getter;

    /**
     * @param string $propertyName
     * @param string $className
     */
    public function __construct(string $propertyName, string $className)
    {
        $this->propertyName = $propertyName;
        $this->className = $className;
        $this->createGetter();
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

        return $this->getter[0]($data);
    }

    /**
     *
     */
    public function __sleep()
    {
        return [
            'propertyName',
            'className'
        ];
    }

    /**
     *
     */
    public function __wakeup()
    {
        $this->createGetter();
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function createGetter(): void
    {
        $propertyName = $this->propertyName;
        $this->getter = [Closure::bind(static function (object $object) use ($propertyName) {
            return $object->$propertyName;
        }, null, $this->className)];
    }
}
