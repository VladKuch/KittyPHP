<?php
namespace Test;
use PHPUnit\Framework\TestCase;

final class SettingTest extends TestCase
{
    public function testHostConfigIsLocalhost()
    {
        $config = new \Core\Config('_settings.yaml');

        $this->assertSame('localhost', $config->db['host']);
    }
}