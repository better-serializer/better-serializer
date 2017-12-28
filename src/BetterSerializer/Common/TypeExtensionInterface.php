<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Common;

use BetterSerializer\DataBind\Reader\Context\ContextInterface as ReadContext;
use BetterSerializer\DataBind\Writer\Context\ContextInterface as WriteContext;

/**
 * Interface ObjectExtensionInterface
 * @package BetterSerializer\Common
 */
interface TypeExtensionInterface extends ExtensionInterface
{

    /**
     * append serialized data into the context
     *
     * @param WriteContext $context
     * @param mixed $data
     * @return mixed
     */
    public function appendData(WriteContext $context, $data): void;

    /**
     * convert from serialized form into object form
     *
     * @param ReadContext $context
     * @return Object
     */
    public function extractData(ReadContext $context);
}
