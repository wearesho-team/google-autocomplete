<?php

namespace Wearesho\GoogleAutocomplete\Tests\Exceptions;

use PHPUnit\Framework\TestCase;

use Wearesho\GoogleAutocomplete\Enums\SearchLanguage;
use Wearesho\GoogleAutocomplete\Exceptions\InvalidQueryType;
use Wearesho\GoogleAutocomplete\Queries\CitySearch;

/**
 * Class InvalidQueryTypeTest
 * @package Wearesho\GoogleAutocomplete\Tests\Exceptions
 * @coversDefaultClass InvalidQueryType
 * @internal
 */
class InvalidQueryTypeTest extends TestCase
{
    protected const TOKEN = 'token';
    protected const INPUT = 'input';

    /** @var InvalidQueryType */
    protected $fakeInvalidQueryType;

    protected function setUp(): void
    {
        $this->fakeInvalidQueryType = new InvalidQueryType(
            new CitySearch(
                static::TOKEN,
                static::INPUT,
                SearchLanguage::UK()
            )
        );
    }

    public function testGetQuery(): void
    {
        $this->assertEquals(
            new CitySearch(
                static::TOKEN,
                static::INPUT,
                SearchLanguage::UK()
            ),
            $this->fakeInvalidQueryType->getQuery()
        );
    }
}
