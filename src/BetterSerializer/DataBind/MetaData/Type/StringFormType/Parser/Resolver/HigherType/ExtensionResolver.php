<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType;

use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnumInterface;
use BetterSerializer\Extension\Registry\ExtensionsCollectionInterface;

/**
 *
 */
final class ExtensionResolver implements TypeClassResolverInterface
{

    /**
     * @var ExtensionsCollectionInterface
     */
    private $extensions;

    /**
     * @param ExtensionsCollectionInterface $extensions
     */
    public function __construct(ExtensionsCollectionInterface $extensions)
    {
        $this->extensions = $extensions;
    }

    /**
     * @param string $potentialHigherType
     * @return TypeClassEnumInterface|null
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function resolveTypeClass(string $potentialHigherType): ?TypeClassEnumInterface
    {
        if (!$this->extensions->hasType($potentialHigherType)) {
            return null;
        }

        return TypeClassEnum::EXTENSION_TYPE();
    }
}
