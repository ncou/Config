<?php
/**
 * This file is part of Berlioz framework.
 *
 * @license   https://opensource.org/licenses/MIT MIT License
 * @copyright 2017 Ronan GIRON
 * @author    Ronan GIRON <https://github.com/ElGigi>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code, to the root.
 */

namespace Berlioz\Config\Tests;

use Berlioz\Config\Exception\ConfigException;
use Berlioz\Config\Exception\NotFoundException;
use Berlioz\Config\JsonConfig;
use PHPUnit\Framework\TestCase;

class JsonConfigTest extends TestCase
{
    /**
     * @throws \Berlioz\Config\Exception\ConfigException
     */
    public function test__constructJson()
    {
        $config = new JsonConfig('{"key": "value"}');
        $this->assertInstanceOf(JsonConfig::class, $config);
    }

    /**
     * @throws \Berlioz\Config\Exception\ConfigException
     */
    public function test__constructFile()
    {
        $config = new JsonConfig(sprintf('%s%s', __DIR__, '/files/config.json'), true);
        $this->assertInstanceOf(JsonConfig::class, $config);
    }

    /**
     * @throws \Berlioz\Config\Exception\ConfigException
     */
    public function test__constructNotExistsFile()
    {
        $this->expectException(NotFoundException::class);
        new JsonConfig(sprintf('%s%s', __DIR__, '/files/config.notexists.json'), true);
    }

    /**
     * @throws \Berlioz\Config\Exception\ConfigException
     */
    public function test__constructMalFormedFile()
    {
        $this->expectException(ConfigException::class);
        new JsonConfig(sprintf('%s%s', __DIR__, '/files/config.malformed.json'), true);
    }

    /**
     * @throws \Berlioz\Config\Exception\ConfigException
     */
    public function testGet()
    {
        $config = new JsonConfig(sprintf('%s%s', __DIR__, '/files/config.json'), true);

        // Test variable
        $this->assertEquals('value1-1', $config->get('var1.var1-1'));
        $this->assertEquals('~~var1.var1-1~~+value2', $config->get('var2'));
        $this->assertEquals('~~extends:config.1.json, config.2.json~~', $config->get('var5'));
        $this->assertEquals('~~include:config.1.json~~', $config->get('var6'));
    }

    /**
     * @throws \Berlioz\Config\Exception\ConfigException
     */
    public function testGetNotFound()
    {
        $this->expectException(NotFoundException::class);
        $config = new JsonConfig(sprintf('%s%s', __DIR__, '/files/config.json'), true);
        $config->get('var100');
    }

    /**
     * @throws \Berlioz\Config\Exception\ConfigException
     */
    public function testHas()
    {
        $config = new JsonConfig(sprintf('%s%s', __DIR__, '/files/config.json'), true);
        $this->assertFalse($config->has('var23.var1'));
        $this->assertTrue($config->has('var5'));
        $this->assertFalse($config->has('var5.var1'));
        $this->assertTrue($config->has('var6'));
    }
}
