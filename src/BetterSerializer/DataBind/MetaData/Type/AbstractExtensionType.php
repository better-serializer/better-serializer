<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

use BetterSerializer\DataBind\MetaData\Type\Parameters\ParametersInterface;

/**
 *
 */
abstract class AbstractExtensionType extends AbstractType implements ExtensionTypeInterface
{

    /**
     * @var string
     */
    protected $customType;

    /**
     * @var ParametersInterface
     */
    protected $parameters;

    /**
     * @param string $type
     * @param ParametersInterface $parameters
     */
    public function __construct(string $type, ParametersInterface $parameters)
    {
        $this->customType = $type;
        $this->parameters = $parameters;
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getCustomType(): string
    {
        return $this->customType;
    }

    /**
     * @return ParametersInterface
     */
    public function getParameters(): ParametersInterface
    {
        return $this->parameters;
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function equals(TypeInterface $type): bool
    {
        if (!$type instanceof self || !parent::equals($type)) {
            return false;
        }

        return $this->customType === $type->customType && $this->parameters->equals($type->getParameters());
    }
}
