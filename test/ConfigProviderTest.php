<?php

namespace MolnTest\ExpressiveCallableCompat;

use Moln\ExpressiveCallableCompat\ConfigProvider;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{

    public function testInvoke()
    {
        $provider = new ConfigProvider();
        $this->assertArrayHasKey('dependencies', $provider());
    }
}
