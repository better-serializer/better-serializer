<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

/**
 * Class String
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
final class UnknownType extends AbstractType
{

    /**
     * @return void
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function initType(): void
    {
        $this->type = TypeEnum::UNKNOWN();
    }

    /**
     * @param TypeInterface $type
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function isCompatibleWith(TypeInterface $type): bool
    {
        return true;
    }
}
