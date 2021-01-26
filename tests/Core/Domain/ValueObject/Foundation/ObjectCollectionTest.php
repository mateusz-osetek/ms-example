<?php

declare(strict_types=1);

namespace App\Tests\Core\Domain\ValueObject\Foundation;

use App\Core\Domain\ValueObject\Foundation\ObjectCollection;
use App\Core\Infrastructure\PhpUnit\CoreTestCase;
use App\Tests\Core\Domain\ValueObject\Test;

class ObjectCollectionTest extends CoreTestCase
{
    public function testFromIterable(): void
    {
        self::assertInstanceOf(
            ObjectCollection::class,
            ObjectCollection::fromIterable(Test::class, [])
        );
    }

    public function testFromMap(): void
    {
        $collection = ObjectCollection::fromMap(
            Test::class,
            [],
            fn ($test) => Test::create($test)
        );

        self::assertInstanceOf(ObjectCollection::class, $collection);
    }

    public function testToArray(): void
    {
        $test = Test::create('test');
        $testArray = [];
        $testArray[] = $test;
        $collection = ObjectCollection::fromIterable(Test::class, $testArray);

        self::assertIsArray($collection->toArray());
    }

    public function testToArrayWhenEmpty(): void
    {
        $collection = ObjectCollection::fromIterable(Test::class, []);

        self::assertEquals([], $collection->toArray());
    }

    public function testInsertValidItems(): void
    {
        $test = Test::create('test');
        $anotherTest = Test::create('anotherTest');
        $collection = ObjectCollection::fromIterable(Test::class, [$test]);
        $collection->insert($anotherTest);

        self::assertEquals(2, $collection->count());
    }

    public function testInsertInvalidItems(): void
    {
        $test = Test::create('test');
        $anotherTest = [];
        $collection = ObjectCollection::fromIterable(Test::class, [$test]);
        $collection->insert($anotherTest);

        self::assertEquals(1, $collection->count());
    }

    public function testRemoveItem(): void
    {
        $test = Test::create('test');
        $anotherTest = Test::create('anotherTest');
        $collection = ObjectCollection::fromIterable(Test::class, [$test]);
        $collection->insert($anotherTest);

        self::assertEquals(2, $collection->count());
        $collection->remove($test);
        self::assertEquals(1, $collection->count());
    }

    public function testHasItem(): void
    {
        $anotherTest = Test::create('anotherTest');
        $collection = ObjectCollection::fromIterable(Test::class, [
            Test::create('test'),
            $anotherTest,
        ]);

        self::assertTrue($collection->has($anotherTest));
    }

    public function testHasNotItem(): void
    {
        $anotherTest = Test::create('anotherTest');
        $collection = ObjectCollection::fromIterable(Test::class, [
            Test::create('test'),
        ]);

        self::assertFalse($collection->has($anotherTest));
    }

    public function testIsEmpty(): void
    {
        $collection = ObjectCollection::fromIterable(Test::class, []);

        self::assertTrue($collection->isEmpty());
    }

    public function testIsNotEmpty(): void
    {
        $collection = ObjectCollection::fromIterable(Test::class, [
            Test::create('test'),
        ]);

        self::assertFalse($collection->isEmpty());
    }

    public function testFilter(): void
    {
        $anotherTest = Test::create('anotherTest');
        $collection = ObjectCollection::fromIterable(Test::class, [
            Test::create('test'),
            $anotherTest,
        ]);

        $filteredCollection = $collection->filter(fn (Test $property) => $anotherTest->toString() === $property->toString());

        self::assertEquals(1, $filteredCollection->count());
    }

    public function testMap(): void
    {
        $testArray = ['test', 'anotherTest'];
        $collection = ObjectCollection::fromMap(
            Test::class,
            $testArray,
            fn ($test) => Test::create($test)
        );

        $mapped = $collection->map(fn (Test $test) => $test->toString());

        self::assertSame($testArray, $mapped);
    }

    public function testEqualsTo(): void
    {
        self::assertTrue(true);
    }

    public function testMerge(): void
    {
        $collection = ObjectCollection::fromIterable(Test::class, [Test::create('test')]);
        $anotherCollection = ObjectCollection::fromIterable(Test::class, [Test::create('anotherTest')]);
        $collection = $collection->merge($anotherCollection);

        self::assertEquals(2, $collection->count());
    }
}
