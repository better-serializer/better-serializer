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
interface TranslatorInterface
{

    /**
     * @param PropertyMetaDataInterface $property
     * @return string
     */
    public function translate(PropertyMetaDataInterface $property): string;
}
