<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit08adcf7a15a02655cb1e67cefb1f0b7e
{
    public static $files = array (
        'd7c9a5138b45deb428e175ae748db2c5' => __DIR__ . '/..' . '/carica/phpcss/src/PhpCss.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PhpCss\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PhpCss\\' => 
        array (
            0 => __DIR__ . '/..' . '/carica/phpcss/src/PhpCss',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit08adcf7a15a02655cb1e67cefb1f0b7e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit08adcf7a15a02655cb1e67cefb1f0b7e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit08adcf7a15a02655cb1e67cefb1f0b7e::$classMap;

        }, null, ClassLoader::class);
    }
}