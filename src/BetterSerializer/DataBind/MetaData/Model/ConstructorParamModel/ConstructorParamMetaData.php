<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel;

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use LogicException;

/**
 *
 */
final class ConstructorParamMetaData implements ConstructorParamMetaDataInterface
{

    /**
     * @var Context\PropertyWithConstructorParamTupleInterface
     */
    private $tuple;

    /**
     * @var TypeInterface
     */
    private $type;

    /**
     * ConstructorParam constructor.
     * @param Context\PropertyWithConstructorParamTupleInterface $tuple
     * @param TypeInterface $type
     * @throws LogicException
     */
    public function __construct(Context\PropertyWithConstructorParamTupleInterface $tuple, TypeInterface $type)
    {
        if (!$tuple->getPropertyType()->isCompatibleWith($type)) {
            throw new LogicException(
                sprintf(
                    "Constructor parameter '%s' and property '%s' have incompatible types.",
                    $tuple->getParamName(),
                    $tuple->getPropertyName()
                )
            );
        }

        $this->tuple = $tuple;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->tuple->getParamName();
    }

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->tuple->getPropertyName();
    }
}
