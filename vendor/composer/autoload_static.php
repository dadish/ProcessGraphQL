<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4328e8c3be249976a7c27f39d3ef6571
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'ProcessWire\\GraphQL\\Test\\' => 25,
            'ProcessWire\\GraphQL\\' => 20,
        ),
        'G' => 
        array (
            'GraphQL\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ProcessWire\\GraphQL\\Test\\' => 
        array (
            0 => __DIR__ . '/../..' . '/test',
        ),
        'ProcessWire\\GraphQL\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'GraphQL\\' => 
        array (
            0 => __DIR__ . '/..' . '/webonyx/graphql-php/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4328e8c3be249976a7c27f39d3ef6571::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4328e8c3be249976a7c27f39d3ef6571::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
