<?php

declare(strict_types=1);

namespace ShipMonk;

use Countable;
use Generator;
use IteratorAggregate;
use JsonSerializable;
use Override;

/**
 * @template T of int|string
 * @extends IteratorAggregate<int, T>
 * @psalm-api
 */
interface LinkedListInterface extends IteratorAggregate, JsonSerializable, Countable
{
    /**
     * @param T $value
     */
    public function insert(int|string $value): void;

    /**
     * @param T $value
     */
    public function removeValue(int|string $value): bool;

    /**
     * @param T $value
     * @return Node<T>|null
     */
    public function find(int|string $value): ?Node;

    public function isEmpty(): bool;

    /**
     * @return array<int, T>
     */
    #[Override]
    public function jsonSerialize(): array;

    /**
     * @return Generator<int, T>
     */
    #[Override]
    public function getIterator(): Generator;
}
