<?php

namespace Wearesho\GoogleAutocomplete\Tests;

use PHPUnit\Framework\TestCase;
use Wearesho\GoogleAutocomplete\EnvironmentConfig;

/**
 * Class EnvironmentConfigTest
 * @package Wearesho\GoogleAutocomplete\Tests
 */
class EnvironmentConfigTest extends TestCase
{
    public function testGetters(): void
    {
        putenv('GOOGLE_SERVICE_AUTOCOMPLETE_URL=https://google.com');
        putenv('GOOGLE_SERVICE_AUTOCOMPLETE_KEY=testApiKey');
        $config = new EnvironmentConfig();
        $this->assertEquals('https://google.com', $config->getUrl());
        $this->assertEquals('testApiKey', $config->getKey());
    }
}
