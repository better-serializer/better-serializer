<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Context\PhpArray;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use RuntimeException;

/**
 *
 */
final class Context implements ContextInterface
{

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var mixed
     */
    private $deserialized;

    /**
     * @param mixed $data
     */
    public function __construct($data = null)
    {
        $this->data = $data;
    }

    /**
     * @return array|mixed
     */
    public function getCurrentValue()
    {
        return $this->data;
    }

    /**
     * @param string|int $key
     * @return mixed
     * @throws RuntimeException
     */
    public function getValue($key)
    {
        if (!array_key_exists($key, $this->data)) {
            throw new RuntimeException(sprintf('Invalid key: %s', $key));
        }

        return $this->data[$key];
    }

    /**
     * @param mixed $deserialized
     */
    public function setDeserialized($deserialized): void
    {
        $this->deserialized = $deserialized;
    }

    /**
     * @return mixed
     */
    public function getDeserialized()
    {
        return $this->deserialized;
    }

    /**
     * @param mixed $key
     * @return ContextInterface|null
     * @throws RuntimeException
     */
    public function readSubContext($key): ?ContextInterface
    {
        if (!array_key_exists($key, $this->data)) {
            throw new RuntimeException(sprintf('Invalid key: %s', $key));
        }

        if ($this->data[$key] === null) {
            return null;
        }

        $subContext = new self();
        $subContext->data = $this->data[$key];

        return $subContext;
    }
}
