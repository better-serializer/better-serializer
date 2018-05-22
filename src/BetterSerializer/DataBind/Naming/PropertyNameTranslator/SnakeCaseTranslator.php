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
final class SnakeCaseTranslator implements TranslatorInterface
{

    /**
     * @param PropertyMetaDataInterface $property
     * @return string
     */
    public function translate(PropertyMetaDataInterface $property): string
    {
        $translated = preg_replace('/([^_])([A-Z])/', '$1_$2', $property->getName());

        return strtolower($translated);
    }
}
