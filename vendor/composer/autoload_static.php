<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita27db57a05fbaf567abee44abebaa3ca
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita27db57a05fbaf567abee44abebaa3ca::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita27db57a05fbaf567abee44abebaa3ca::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
