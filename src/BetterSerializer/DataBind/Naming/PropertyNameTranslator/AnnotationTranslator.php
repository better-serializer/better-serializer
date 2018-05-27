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
final class AnnotationTranslator implements TranslatorInterface
{

    /**
     * @var TranslatorInterface
     */
    private $delegate;

    /**
     * @param TranslatorInterface $delegate
     */
    public function __construct(TranslatorInterface $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * @param PropertyMetaDataInterface $property
     * @return string
     */
    public function translate(PropertyMetaDataInterface $property): string
    {
        $serializationName = $property->getSerializationName();

        if ($serializationName !== '') {
            return $serializationName;
        }

        return $this->delegate->translate($property);
    }
}
