<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\UseStatement;

use LogicException;

/**
 * Class UseStatements
 * @author mfris
 * @package BetterSerializer\Reflection\UseStatement
 */
final class UseStatements implements UseStatementsInterface
{

    /**
     * @var UseStatement[]
     */
    private $useStatements;

    /**
     * @var UseStatement[]
     */
    private $indexByIdentifier = [];

    /**
     * @var UseStatement[]
     */
    private $indexByAlias = [];

    /**
     * UseStatements constructor.
     * @param UseStatement[] $useStatements
     */
    public function __construct(array $useStatements)
    {
        $this->useStatements = $useStatements;
        $this->index();
    }

    /**
     * @param string $identifier
     * @return bool
     * @throws LogicException
     */
    public function hasByIdentifier(string $identifier): bool
    {
        if ($identifier === '') {
            throw new LogicException('Identifier cannot be empty.');
        }

        return isset($this->indexByIdentifier[$identifier]);
    }

    /**
     * @param string $identifier
     * @return UseStatementInterface|null
     * @throws LogicException
     */
    public function getByIdentifier(string $identifier): ?UseStatementInterface
    {
        if ($identifier === '') {
            throw new LogicException('Identifier cannot be empty.');
        }

        return $this->indexByIdentifier[$identifier] ?? null;
    }

    /**
     * @param string $alias
     * @return UseStatementInterface|null
     * @throws LogicException
     */
    public function getByAlias(string $alias): ?UseStatementInterface
    {
        if ($alias === '') {
            throw new LogicException('Alias cannot be empty.');
        }

        return $this->indexByAlias[$alias] ?? null;
    }

    /**
     * @param string $alias
     * @return bool
     * @throws LogicException
     */
    public function hasByAlias(string $alias): bool
    {
        if ($alias === '') {
            throw new LogicException('Alias cannot be empty.');
        }

        return isset($this->indexByAlias[$alias]);
    }

    /**
     *
     */
    private function index(): void
    {
        foreach ($this->useStatements as $useStatement) {
            $this->indexByIdentifier[$useStatement->getIdentifier()] = $useStatement;
            $alias = $useStatement->getAlias();

            if ($alias !== '') {
                $this->indexByAlias[$alias] = $useStatement;
            }
        }
    }
}
