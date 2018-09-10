<?php

namespace Wearesho\GoogleAutocomplete\Tests;

use Wearesho\GoogleAutocomplete\Location;

use PHPUnit\Framework\TestCase;

/**
 * Class LocationTest
 * @package Wearesho\GoogleAutocomplete\Tests
 * @coversDefaultClass Location
 * @internal
 */
class LocationTest extends TestCase
{
    protected const VALUE = 'testValue';

    /** @var Location */
    protected $fakeLocation;

    protected function setUp(): void
    {
        $this->fakeLocation = new Location(static::VALUE);
    }

    public function testToString(): void
    {
        $this->assertEquals(
            static::VALUE,
            $this->fakeLocation->__toString()
        );
        $this->assertEquals(
            static::VALUE,
            (string)$this->fakeLocation
        );
    }

    public function testJsonSerialize(): void
    {
        $this->assertEquals(
            static::VALUE,
            $this->fakeLocation->jsonSerialize()
        );
    }

    public function testGetValue(): void
    {
        $this->assertEquals(
            static::VALUE,
            $this->fakeLocation->getValue()
        );
    }
}
