<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Naming\PropertyNameTranslator;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;

/**
 *
 */
final class CamelCaseTranslator implements TranslatorInterface
{

    /**
     * @param PropertyMetaDataInterface $property
     * @return string
     */
    public function translate(PropertyMetaDataInterface $property): string
    {
        return preg_replace_callback('/(_+)([a-zA-Z])/', function (array $match) {
            return strtoupper($match[2]);
        }, $property->getName());
    }
}
