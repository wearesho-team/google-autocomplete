<?php

namespace Wearesho\GoogleAutocomplete\Tests;

use PHPUnit\Framework\TestCase;
use Wearesho\GoogleAutocomplete\SearchData;

/**
 * Class SearchDataTest
 * @package Wearesho\GoogleAutocomplete\Tests
 */
class SearchDataTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid language: en
     */
    public function testInvalidLanguage(): void
    {
        new SearchData('some value', 'city', 'en');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid address type: invalid type
     */
    public function testInvalidType(): void
    {
        new SearchData('some value', 'invalid type', 'uk');
    }

    public function testGetters(): void
    {
        $searchData = new SearchData('some value', 'city', 'uk');
        $this->assertEquals('some value', $searchData->getInput());
        $this->assertEquals('city', $searchData->getType());
        $this->assertEquals('uk', $searchData->getLanguage());
    }
}
