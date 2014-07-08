<?php

namespace XTC;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

class Kernel {
    private static $kernel;
    private static $request;
    private static $response;

    public static function start(){
        $loader = require_once __DIR__.'/../../../var/bootstrap.php.cache';
        /*
        $apcLoader = new ApcClassLoader('sf2', $loader);
        $loader->unregister();
        $apcLoader->register(true);
        */
        Debug::enable();

        require_once __DIR__.'/../../../app/AppKernel.php';

        static::$kernel = new \AppKernel('dev', true);
        static::$kernel->loadClassCache();
        //static::$kernel = new AppCache($kernel);
        static::$kernel->boot();
    }
    public static function handle(){
        static::$request = Request::createFromGlobals();
        static::$response = static::$kernel->handle(static::$request);
        static::$response->send();
    }
    public static function stop(){
        static::$kernel->terminate(static::$request, static::$response);
    }
} 