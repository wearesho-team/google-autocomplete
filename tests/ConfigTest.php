<?php

namespace Wearesho\GoogleAutocomplete\Tests;

use PHPUnit\Framework\TestCase;
use Wearesho\GoogleAutocomplete\Config;

/**
 * Class ConfigTest
 * @package Wearesho\GoogleAutocomplete\Tests
 */
class ConfigTest extends TestCase
{
    public function testGetters(): void
    {
        $config = new Config('https://google.com/', 'test api key');
        $this->assertEquals('https://google.com/', $config->getUrl());
        $this->assertEquals('test api key', $config->getKey());
    }
}
