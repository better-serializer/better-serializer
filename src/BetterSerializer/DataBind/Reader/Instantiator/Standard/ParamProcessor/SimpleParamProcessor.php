<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;

/**
 *
 */
final class SimpleParamProcessor implements ParamProcessorInterface
{

    /**
     * @var string
     */
    private $key;

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @param ContextInterface $context
     * @return mixed
     */
    public function processParam(ContextInterface $context)
    {
        return $context->getValue($this->key);
    }
}
