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
}
