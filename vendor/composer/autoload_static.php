<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit885835fe1ce0f3a406b206bbed2ea2e2
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'Z' => 
        array (
            'Zend\\Diactoros\\' => 15,
        ),
        'W' => 
        array (
            'Windwalker\\Uri\\' => 15,
            'Windwalker\\Structure\\' => 21,
            'Windwalker\\Renderer\\' => 20,
            'Windwalker\\Loader\\' => 18,
            'Windwalker\\Http\\' => 16,
            'Windwalker\\Edge\\' => 16,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Contracts\\Translation\\' => 30,
            'Symfony\\Component\\Translation\\' => 30,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Zend\\Diactoros\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-diactoros/src',
        ),
        'Windwalker\\Uri\\' => 
        array (
            0 => __DIR__ . '/..' . '/windwalker/uri',
        ),
        'Windwalker\\Structure\\' => 
        array (
            0 => __DIR__ . '/..' . '/windwalker/structure',
        ),
        'Windwalker\\Renderer\\' => 
        array (
            0 => __DIR__ . '/..' . '/windwalker/renderer',
        ),
        'Windwalker\\Loader\\' => 
        array (
            0 => __DIR__ . '/..' . '/windwalker/loader',
        ),
        'Windwalker\\Http\\' => 
        array (
            0 => __DIR__ . '/..' . '/windwalker/http',
        ),
        'Windwalker\\Edge\\' => 
        array (
            0 => __DIR__ . '/..' . '/windwalker/edge',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Contracts\\Translation\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/translation-contracts',
        ),
        'Symfony\\Component\\Translation\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/translation',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
    );

    public static $fallbackDirsPsr4 = array (
        0 => __DIR__ . '/..' . '/nesbot/carbon/src',
    );

    public static $prefixesPsr0 = array (
        'U' => 
        array (
            'UpdateHelper\\' => 
            array (
                0 => __DIR__ . '/..' . '/kylekatarnls/update-helper/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit885835fe1ce0f3a406b206bbed2ea2e2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit885835fe1ce0f3a406b206bbed2ea2e2::$prefixDirsPsr4;
            $loader->fallbackDirsPsr4 = ComposerStaticInit885835fe1ce0f3a406b206bbed2ea2e2::$fallbackDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit885835fe1ce0f3a406b206bbed2ea2e2::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
