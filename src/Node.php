<?php

declare(strict_types=1);

namespace ShipMonk;

/**
 * @template T of int|string
 */
final class Node
{
    /** @var Node<T>|null */
    public ?Node $next = null;

    /**
     * @param int|string $value
     */
    public function __construct(public readonly int|string $value) {}

    /**
     * returns -1, 0, or 1 based on comparison.
     * @param Node<T> $toCompare
     * @return int
     */
    public function compare(Node $toCompare): int
    {
        if (is_string($this->value) && is_string($toCompare->value)) {
            return strcasecmp($this->value, $toCompare->value) <=> 0;
        }

        return $this->value <=> $toCompare->value;
    }
}
