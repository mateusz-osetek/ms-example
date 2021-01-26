<?php

declare(strict_types=1);

namespace App\Tests\Core\Domain\ValueObject\Foundation;

use App\Core\Domain\ValueObject\Foundation\Json;
use App\Core\Infrastructure\PhpUnit\CoreTestCase;

class JsonTest extends CoreTestCase
{
    public function testFromSimpleString(): void
    {
        $json = Json::fromString('{"some":"value"}');

        self::assertInstanceOf(Json::class, $json);
        self::assertSame(['some' => 'value'], $json->toArray());
    }

    public function testFromSimpleArray(): void
    {
        $json = Json::fromArray(['some' => 'value']);

        self::assertInstanceOf(Json::class, $json);
        self::assertSame('{"some":"value"}', $json->toString());
    }

    public function testFromComplexString(): void
    {
        $complexArray = [
            'values' => [
                ['value' => 'one'],
                ['value' => 'two'],
            ],
            'mixed' => ['one', 2, 3, true],
        ];

        $json = Json::fromString('{"values":[{"value":"one"},{"value":"two"}],"mixed":["one",2,3,true]}');

        self::assertInstanceOf(Json::class, $json);
        self::assertSame($complexArray, $json->toArray());
    }

    public function testFromComplexArray(): void
    {
        $complexArray = [
            'values' => [
                ['value' => 'one'],
                ['value' => 'two'],
            ],
            'mixed' => ['one', 2, 3, true],
        ];

        $json = Json::fromArray($complexArray);

        self::assertInstanceOf(Json::class, $json);
        self::assertSame('{"values":[{"value":"one"},{"value":"two"}],"mixed":["one",2,3,true]}', $json->toString());
    }

    public function testFromInvalidString(): void
    {
        $json = Json::fromString('"some":"value}');

        self::assertNull($json);
    }

    public function testEquals(): void
    {
        $json = Json::fromString('{"some":"value"}');
        $sameJson = Json::fromArray(['some' => 'value']);

        self::assertTrue($json->equalsTo($json));
        self::assertTrue($json->equalsTo($sameJson));
    }

    public function testNotEquals(): void
    {
        $json = Json::fromString('{"some":"value"}');
        $anotherJson = Json::fromArray(['another' => 'value']);

        self::assertFalse($json->equalsTo($anotherJson));
    }
}
