<?php

namespace Luna\services;

use Luna\Core\Service;
use Twig_Loader_Filesystem;
use Twig_Environment;

class View extends Service
{

    private  static $global_default; //twig

    private static $global_configs; // all drivers config

    private $te;

    public static function init($info = null)
    {
        parent::init($info);

        require_once 'drivers' .DS . 'viewDriver.php';
    }

    public static function config($config = null)
    {
        parent::config();

        self::$global_default = $config['default'];

        self::$global_configs = $config['packages'];
    }

    public function __construct($driver = null, $config = null)
    {
        $driver = ( empty($driver) or ! array_key_exists($driver, self::$global_configs) ) ? self::$global_default : $driver ;

        $driver_configs = (empty($config)) ? self::$global_configs[$driver] : $config;

        $driver = $driver . '_viewDriver';

        require_once 'drivers' . DS . $driver . '.php';

        $this->te = new $driver($driver_configs);
    }

    public function assign($key, $value = null, ...$data)
    {
        return $this->te->set($key, $value, ...$data);
    }

    public function render($file, $data = null)
    {
        return $this->te->render($file, $data);
    }

}