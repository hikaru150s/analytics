<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbe96f6a4649b3caff1e56222eb1dd0b7
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Phpml\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Phpml\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-ai/php-ml/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbe96f6a4649b3caff1e56222eb1dd0b7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbe96f6a4649b3caff1e56222eb1dd0b7::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
