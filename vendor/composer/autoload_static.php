<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3c6d31ab481f405bb8fc821f2d24e2e1
{
    public static $prefixLengthsPsr4 = array (
        'Y' => 
        array (
            'Youshido\\GraphQL\\' => 17,
        ),
        'P' => 
        array (
            'ProcessWire\\GraphQL\\Test\\' => 25,
            'ProcessWire\\GraphQL\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Youshido\\GraphQL\\' => 
        array (
            0 => __DIR__ . '/..' . '/youshido/graphql/src',
        ),
        'ProcessWire\\GraphQL\\Test\\' => 
        array (
            0 => __DIR__ . '/../..' . '/test',
        ),
        'ProcessWire\\GraphQL\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3c6d31ab481f405bb8fc821f2d24e2e1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3c6d31ab481f405bb8fc821f2d24e2e1::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
