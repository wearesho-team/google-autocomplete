<?php

namespace Wearesho\GoogleAutocomplete\Tests;

use PHPUnit\Framework\TestCase;

use Wearesho\GoogleAutocomplete\ConfigInterface;
use Wearesho\GoogleAutocomplete\EnvironmentConfig;

/**
 * Class EnvironmentConfigTest
 * @package Wearesho\GoogleAutocomplete\Tests
 * @coversDefaultClass EnvironmentConfig
 * @internal
 */
class EnvironmentConfigTest extends TestCase
{
    protected const URL = 'https://google.com';
    protected const KEY = 'testKey';

    /** @var EnvironmentConfig */
    protected $fakeEnvironmentConfig;

    protected function setUp(): void
    {
        $this->fakeEnvironmentConfig = new EnvironmentConfig('GOOGLE_AUTOCOMPLETE_');
    }

    public function testExistKey(): void
    {
        putenv('GOOGLE_AUTOCOMPLETE_KEY=' . static::KEY);

        $this->assertEquals(
            static::KEY,
            $this->fakeEnvironmentConfig->getKey()
        );
    }

    /**
     * @expectedException \Horat1us\Environment\MissingEnvironmentException
     * @expectedExceptionMessage Missing environment key GOOGLE_AUTOCOMPLETE_KEY
     */
    public function testNotExistKey(): void
    {
        putenv('GOOGLE_AUTOCOMPLETE_KEY');

        $this->fakeEnvironmentConfig->getKey();
    }

    public function testExistUrl(): void
    {
        putenv('GOOGLE_AUTOCOMPLETE_URL=' . static::URL);

        $this->assertEquals(
            static::URL,
            $this->fakeEnvironmentConfig->getUrl()
        );
    }

    public function testNotExistUrl(): void
    {
        putenv('GOOGLE_AUTOCOMPLETE_URL');

        $this->assertEquals(
            ConfigInterface::URL,
            $this->fakeEnvironmentConfig->getUrl()
        );
    }
}
