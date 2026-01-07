<?php

declare(strict_types=1);

namespace ShipMonk;

use Generator;
use Override;

/**
 * @template T of int|string
 * @implements LinkedListInterface<T>
 * @psalm-api
 */
final class SortedLinkedList implements LinkedListInterface
{
    /**
     * @var Node<T>|null
     */
    private ?Node $head = null;
    private int $count = 0;

    public function __construct(
        private readonly SortOrder $sortOrder = SortOrder::ASC,
        private ?ListType $type = null,
    ) {}

    /**
     * @param T $value
     * @return void
     */
    #[Override]
    public function insert(int|string $value): void
    {
        $this->validateValueType($value);

        $newNode = new Node($value);

        $this->count++;

        if ($this->isEmpty()) {
            $this->head = $newNode;
            return;
        }

        $comparatorResult = $this->sortOrder->getComparatorResult();

        //insert as first
        if ($newNode->compare($this->head) === $comparatorResult) {
            $newNode->next = $this->head;
            $this->head = $newNode;
            return;
        }


        //search for place to insert
        $currentNode = $this->head;
        while ($currentNode->next !== null && $newNode->compare($currentNode->next) !== $comparatorResult) {
            $currentNode = $currentNode->next;
        }

        $newNode->next = $currentNode->next;
        $currentNode->next = $newNode;

    }

    /**
     * @param T $value
     * @return bool
     */
    #[Override]
    public function removeValue(int|string $value): bool
    {
        if ($this->isEmpty()) {
            return false;
        }

        if ($this->head->value === $value) {
            $this->head = $this->head->next;
            $this->count--;
            return true;
        }

        $currentNode = $this->head;
        while ($currentNode->next !== null) {
            if ($currentNode->next->value === $value) {
                $currentNode->next = $currentNode->next->next;
                $this->count--;
                return true;
            }
            $currentNode = $currentNode->next;
        }
        return false;
    }

    /**
     * @param T $value
     *
     * @psalm-return Node<T>|null
     */
    #[Override]
    public function find(int|string $value): ?Node
    {
        $this->validateValueType($value);

        if ($this->isEmpty()) {
            return null;
        }

        $searchNode = new Node($value);
        $current = $this->head;

        $comparatorResult = $this->sortOrder->getComparatorResult();

        while ($current !== null) {
            $compare = $searchNode->compare($current);
            if ($compare === 0) {
                return $current;
            }

            //stop if already passed all possible position searched element could be
            if ($compare === $comparatorResult) {
                break;
            }
            $current = $current->next;
        }

        return null;
    }

    /**
     * @return Generator<int, T>
     */
    #[Override]
    public function getIterator(): Generator
    {
        $currentNode = $this->head;
        while ($currentNode !== null) {
            yield $currentNode->value;
            $currentNode = $currentNode->next;
        }
    }

    /**
     * @return array<int, T>
     */
    #[Override]
    public function jsonSerialize(): array
    {
        return iterator_to_array($this);
    }

    /**
     * @return bool
     */
    #[Override]
    public function isEmpty(): bool
    {
        return $this->head === null;
    }

    /**
     * @return int
     */
    #[\Override]
    public function count(): int
    {
        return $this->count;
    }

    /**
     * @param array<int,T> $values
     * @return void
     */
    public function insertValues(array $values): void
    {
        foreach ($values as $value) {
            $this->insert($value);
        }
    }

    /**
     * @template TValue
     * @param array<int, TValue> $values
     * @param SortOrder $sortOrder
     * @return self<TValue>
     */
    public static function fromArray(array $values, SortOrder $sortOrder = SortOrder::ASC): self
    {
        $list = new self($sortOrder);
        $list->insertValues($values);
        return $list;
    }


    /**
     * @param int|string $value
     * @return void
     */
    private function validateValueType(int|string $value): void
    {
        $typeName = gettype($value);

        $newValueType = ListType::from($typeName);
        $this->type ??= $newValueType;

        if ($this->type !== $newValueType) {
            throw new \InvalidArgumentException(sprintf("Incorrect value type. This is %s type of list", $typeName));
        }
    }
}
