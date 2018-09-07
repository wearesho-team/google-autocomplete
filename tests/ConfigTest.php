<?php

namespace Wearesho\GoogleAutocomplete\Tests;

use PHPUnit\Framework\TestCase;

use Wearesho\GoogleAutocomplete\Config;

/**
 * Class ConfigTest
 * @package Wearesho\GoogleAutocomplete\Tests
 * @coversDefaultClass Config
 * @internal
 */
class ConfigTest extends TestCase
{
    protected const URL = 'https://google.com/';
    protected const KEY = 'testKey';

    /** @var Config */
    protected $fakeConfig;

    protected function setUp(): void
    {
        $this->fakeConfig = new Config(static::URL, static::KEY);
    }

    public function testGetUrl(): void
    {
        $this->assertEquals(
            static::URL,
            $this->fakeConfig->getUrl()
        );
    }

    public function testGetKey(): void
    {
        $this->assertEquals(
            static::KEY,
            $this->fakeConfig->getKey()
        );
    }
}
