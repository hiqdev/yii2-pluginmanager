<?php

/*
 * Plugin Manager for Yii2
 *
 * @link      https://github.com/hiqdev/yii2-pluginmanager
 * @package   yii2-pluginmanager
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\yii2\pluginmanager\tests\unit;

/**
 * Plugin test suite.
 */
class PluginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Plugin
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new FakePlugin();
    }

    protected function tearDown()
    {
    }

    public function testInit()
    {
        $this->object->init();
        $this->assertSame([
            'aliases' => [
                '@dns'  => '/dns/',
                '@more' => '/more',
            ],
            'modules' => [
                'dns' => 'dns config here',
            ],
            'components' => [
                'i18n' => 'i18n config here',
            ],
        ], $this->object->getItems());
    }
}
