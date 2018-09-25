<?php

namespace Wearesho\GoogleAutocomplete\Tests;

use PHPUnit\Framework\TestCase;

use Wearesho\GoogleAutocomplete\Location;
use Wearesho\GoogleAutocomplete\LocationCollection;

/**
 * Class LocationCollectionTest
 * @package Wearesho\GoogleAutocomplete\Tests
 * @coversDefaultClass LocationCollection
 * @internal
 */
class LocationCollectionTest extends TestCase
{
    protected const VALUE = 'test separated value';

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

    public function testExcludeDuplicates(): void
    {
        $this->fakeLocationCollection = new LocationCollection();

        for ($i = 0; $i < 200; $i++) {
            $this->fakeLocationCollection->append(new Location(static::VALUE));
        }

        $this->assertCount(200, $this->fakeLocationCollection);
        $this->fakeLocationCollection->excludeDuplicates();
        $this->assertCount(1, $this->fakeLocationCollection);
    }
}
