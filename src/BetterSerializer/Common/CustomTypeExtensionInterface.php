<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Common;

use BetterSerializer\DataBind\MetaData\Type\Parameters\ParametersInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface as ReadContext;
use BetterSerializer\DataBind\Writer\Context\ContextInterface as WriteContext;

/**
 * Interface ObjectExtensionInterface
 * @package BetterSerializer\Common
 */
interface CustomTypeExtensionInterface extends ExtensionInterface
{

    /**
     * @param ParametersInterface $parameters
     */
    public function __construct(ParametersInterface $parameters);

    /**
     * append serialized data into the context
     *
     * @param WriteContext $context
     * @param string $key
     * @param mixed $data
     * @return mixed
     */
    public function appendData(WriteContext $context, string $key, $data): void;

    /**
     * convert from serialized form into object form
     *
     * @param ReadContext $context
     * @param string $key
     * @return Object
     */
    public function extractData(ReadContext $context, string $key);
}
