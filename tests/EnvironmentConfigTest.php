<?php

namespace Wearesho\Notifications\Tests;

use PHPUnit\Framework\TestCase;
use Wearesho\Notifications\EnvironmentConfig;

/**
 * Class EnvironmentConfigTest
 * @package Wearesho\Notifications\Tests
 */
class EnvironmentConfigTest extends TestCase
{
    public function testGetters(): void
    {
        $config = new EnvironmentConfig();
        putenv('WEARESHO_NOTIFICATIONS_URL=asfd');
        $this->assertEquals('asfd', $config->getUrl());
        $this->assertNull($config->getServiceKey());
        putenv('WEARESHO_NOTIFICATIONS_SERVICE_KEY=asdf');
        $this->assertEquals('asdf', $config->getServiceKey());
    }
}
