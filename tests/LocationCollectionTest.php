<?php

namespace Wearesho\GoogleAutocomplete\Tests;

use Wearesho\GoogleAutocomplete\Location;
use Wearesho\GoogleAutocomplete\LocationCollection;

use PHPUnit\Framework\TestCase;

/**
 * Class LocationCollectionTest
 * @package Wearesho\GoogleAutocomplete\Tests
 * @coversDefaultClass LocationCollection
 * @internal
 */
class LocationCollectionTest extends TestCase
{
    protected const VALUE = 'testValue';

    /** @var LocationCollection */
    protected $fakeLocationCollection;

    protected function setUp(): void
    {
        $this->fakeLocationCollection = new LocationCollection([
            new Location(static::VALUE),
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Element Exception must be instance of Wearesho\GoogleAutocomplete\Location
     */
    public function testAppend(): void
    {
        $this->fakeLocationCollection->append(new \Exception());
    }

    public function testValues(): void
    {
        $this->fakeLocationCollection->append(new Location(static::VALUE));
        $this->fakeLocationCollection->append(new Location(static::VALUE));

        $this->assertArrayHasKey(0, (array)$this->fakeLocationCollection);
        $this->assertArrayHasKey(1, (array)$this->fakeLocationCollection);
        $this->assertArrayHasKey(2, (array)$this->fakeLocationCollection);

        $this->fakeLocationCollection->offsetUnset(1);

        $this->assertArrayHasKey(0, (array)$this->fakeLocationCollection);
        $this->assertArrayNotHasKey(1, (array)$this->fakeLocationCollection);
        $this->assertArrayHasKey(2, (array)$this->fakeLocationCollection);

        $this->fakeLocationCollection = $this->fakeLocationCollection->values();

        $this->assertArrayHasKey(0, (array)$this->fakeLocationCollection);
        $this->assertArrayHasKey(1, (array)$this->fakeLocationCollection);
        $this->assertArrayNotHasKey(2, (array)$this->fakeLocationCollection);
    }
}
