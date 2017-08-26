<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\UseStatement;

/**
 * Class UseStatement
 * @author mfris
 * @package BetterSerializer\Reflection
 */
final class UseStatement implements UseStatementInterface
{

    /**
     * @var string
     */
    private $fqdn;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $namespace;

    /**
     * UseStatement constructor.
     * @param string $fqdn
     * @param string $alias
     */
    public function __construct(string $fqdn, string $alias = '')
    {
        $this->fqdn = $fqdn;
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getFqdn(): string
    {
        return $this->fqdn;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        if ($this->identifier === null) {
            $this->splitFqdn();
        }

        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        if ($this->namespace === null) {
            $this->splitFqdn();
        }

        return $this->namespace;
    }

    /**
     *
     */
    private function splitFqdn(): void
    {
        $parts = explode('\\', $this->fqdn);
        $this->identifier = array_pop($parts);
        $this->namespace = implode('\\', $parts);
    }
}
