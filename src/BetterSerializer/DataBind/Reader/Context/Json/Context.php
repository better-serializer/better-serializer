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
     * @param string|int $key
     * @return mixed
     * @throws RuntimeException
     */
    public function readValue($key)
    {
        if (!array_key_exists($key, $this->data)) {
            throw new RuntimeException(sprintf('Invalid key: %s', $key));
        }

        return $this->data[$key];
    }

    /**
     * @param mixed $key
     * @return ContextInterface
     * @throws RuntimeException
     */
    public function readSubContext($key): ContextInterface
    {
        if (!array_key_exists($key, $this->data)) {
            throw new RuntimeException(sprintf('Invalid key: %s', $key));
        }

        $subContext = new self();
        $subContext->data = &$this->data[$key];

        return $subContext;
    }
}
