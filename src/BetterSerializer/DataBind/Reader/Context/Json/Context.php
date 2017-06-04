<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Context\Json;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use RuntimeException;

/**
 * Class Context
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\ValueWriter\Json
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
     * Context constructor.
     * @param string $json
     */
    public function __construct(string $json = '')
    {
        if ($json !== '') {
            $this->data = json_decode($json, true);
        }
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
        $subContext->data = &$this->data[$key];

        return $subContext;
    }
}
