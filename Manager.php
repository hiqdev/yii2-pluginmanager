<?php
/**
 * @link    http://hiqdev.com/yii2-pluginmanager
 * @license http://hiqdev.com/yii2-pluginmanager/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hiqdev\pluginmanager;

use Yii;
use yii\base\BootstrapInterface;

/**
 * Plugin Manager
 *
 * Usage, in config:
 * ~~~
 *
 * ~~~
 */
class Manager extends \hiqdev\collection\Object implements BootstrapInterface
{
    /**
     * Adds given plugins. Doesn't delete old.
     */
    public function setPlugins(array $plugins)
    {
        return $this->setItem('plugins', array_merge((array)$this->rawItem('plugins'), $plugins));
    }

    /**
     * @var boolean is already bootstrapped.
     */
    protected $_isBootstrapped = false;

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($this->_isBootstrapped) {
            return;
        }
        $cached = null;
        if ($cached) {
            $this->mset($cached);
        } else {
            foreach ($app->extensions as $name => $extension) {
                foreach ($extension['alias'] as $alias => $path) {
                    $class = strtr(substr($alias,1) . '/' . 'Plugin', '/','\\');
                    if (!class_exists($class)) {
                        continue;
                    }
                    $ref = new \ReflectionClass($class);
                    if ($ref->isSubclassOf('hiqdev\pluginmanager\Plugin')) {
                        $plugin = Yii::createObject($class);
                        if ($plugin instanceof BootstrapInterface) {
                            $plugin->bootstrap($app);
                        }
                        $this->setPlugins([$name => $plugin]);
                        foreach ($plugin->getItems() as $k => $v) {
                            $this->_items[$k] = array_merge((array)$this->_items[$k], $v);
                        }
                    }
                }
            }
            $cached = $this->toArray();
        }
        $app->modules = array_merge($this->modules, $app->modules);
        $this->_isBootstrapped = true;
        if ($app->has('menuManager')) {
            $app->menuManager->bootstrap($app);
        }
        if ($app->has('themeManager')) {
            $app->themeManager->bootstrap($app);
        }
    }

}
