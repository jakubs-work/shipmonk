<?php

declare(strict_types=1);

namespace Test;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ShipMonk\ListType;
use ShipMonk\Node;
use ShipMonk\SortedLinkedList;
use ShipMonk\SortOrder;

/**
 * @psalm-api
 */
class SortedLinkedListTest extends TestCase
{
    #[DataProvider('invalidInitializedTypeScenarios')]
    public function testInsertedValueOfDifferentType(ListType $listType, int|string $valueToInsert): void
    {
        $list  = new SortedLinkedList(type: $listType);
        $this->expectException(InvalidArgumentException::class);
        $list->insert($valueToInsert);
    }

    #[DataProvider('invalidDetectedTypeScenarios')]
    public function testDifferentValuesTypes(int|string $fistValue, int|string $secondValue): void
    {
        $list  = new SortedLinkedList();
        $list->insert($fistValue);
        $this->expectException(InvalidArgumentException::class);
        $list->insert($secondValue);
    }

    public function testEmptyList(): void
    {
        $list = new SortedLinkedList();
        $this->assertTrue($list->isEmpty());
        $this->assertCount(0, $list->jsonSerialize());
    }

    #[DataProvider('insertNodesValues')]
    public function testInsertNodesDesc(SortOrder $sortOrder, array $values, array $expected): void
    {
        $list  = new SortedLinkedList($sortOrder);
        foreach ($values as $value) {
            $list->insert($value);
        }
        $this->assertEquals($expected, $list->jsonSerialize());
    }

    #[DataProvider('removeNodeByValueScenarios')]
    public function testRemoveNodeByValueDesc(SortOrder $sortOrder, array $values, int|string $toRemove, array $expected): void
    {
        $list  = new SortedLinkedList($sortOrder);
        foreach ($values as $value) {
            $list->insert($value);
        }
        $list->removeValue($toRemove);
        $this->assertEquals($expected, $list->jsonSerialize());
    }

    public function testInsertArray()
    {
        $array = ['banana', 'apple', 'cherry'];
        $list  = new SortedLinkedList();
        $list->insertValues($array);
        $this->assertEquals(['apple', 'banana', 'cherry'], $list->jsonSerialize());
    }

    public function testInsertMixedArray()
    {
        $array = ['banana', 1000, 'cherry'];
        $list  = new SortedLinkedList();
        $this->expectException(InvalidArgumentException::class);
        $list->insertValues($array);
    }

    public function testFromArray()
    {
        $array = ['banana', 'apple', 'cherry'];
        $list  = SortedLinkedList::fromArray($array);
        $this->assertEquals(['apple', 'banana', 'cherry'], $list->jsonSerialize());
    }

    #[DataProvider('findOnListScenarios')]
    public function testFindOnList(array $values, int|string $search, ?Node $searchResult): void
    {
        $list = SortedLinkedList::fromArray($values);
        $this->assertEquals($list->find($search)?->value, $searchResult?->value);
    }
    public static function invalidInitializedTypeScenarios(): iterable
    {
        yield 'initialized integer list type - inserting string' => [ListType::INTEGER, 'stringValue'];
        yield 'initialized string list type - inserting integer' => [ListType::STRING, 42];
    }

    public static function invalidDetectedTypeScenarios(): iterable
    {
        yield 'detected integer list type - inserting string' => [42, 'stringValue'];
        yield 'detected string list type - inserting integer' => ['string', 42];
    }

    public static function insertNodesValues(): iterable
    {
        yield 'ascending standard' => [
            SortOrder::ASC,
            [60, 70, 80, 65, 55],
            [55, 60, 65, 70, 80],
        ];

        yield 'descending standard' => [
            SortOrder::DESC,
            [60, 70, 80, 65, 55],
            [80, 70, 65, 60, 55],
        ];

        yield 'duplicates' => [
            SortOrder::ASC,
            [10, 20, 10, 30],
            [10, 10, 20, 30],
        ];

        yield 'string alphabetical' => [
            SortOrder::ASC  ,
            ['banana', 'apple', 'cherry'],
            ['apple', 'banana', 'cherry'],
        ];

        yield 'string alphabetical desc' => [
            SortOrder::DESC,
            ['banana', 'apple', 'cherry'],
            ['cherry','banana', 'apple'],
        ];

        yield 'single element' => [
            SortOrder::ASC,
            [42],
            [42],
        ];
    }

    public static function removeNodeByValueScenarios(): iterable
    {
        yield 'remove from begining' => [
            SortOrder::ASC,
            [60, 70, 80, 65, 55],
            55,
            [60, 65, 70, 80],
        ];

        yield 'remove from middle' => [
            SortOrder::ASC,
            [60, 70, 80, 65, 55],
            65,
            [55, 60, 70, 80],
        ];

        yield 'remove from the end' => [
            SortOrder::ASC,
            [60, 70, 80, 65, 55],
            80,
            [55, 60, 65, 70],
        ];

        yield 'remove last element' => [
            SortOrder::ASC,
            [42],
            42,
            [],
        ];

        yield 'remove string from integer list' => [
            SortOrder::ASC,
            [60, 70, 80, 65, 55],
            "80",
            [55, 60, 65, 70, 80],
        ];
    }

    public static function findOnListScenarios()
    {
        yield 'empty list' => [
            [],
            4,
            null,
        ];

        yield 'find at the begining of the list' => [
            [60, 70, 80, 65, 55],
            60,
            new Node(60),
        ];

        yield 'find at the end of the list' => [
            [60, 70, 80, 65, 55],
            80,
            new Node(80),
        ];

        yield 'string not found in int list' => [
            ['banana', 'apple', 'cherry'],
            "apple",
            new Node("apple"),
        ];
    }
}
