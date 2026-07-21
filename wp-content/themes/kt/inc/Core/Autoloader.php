<?php
namespace KT\Core;

class Autoloader {

    public static function register() {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    public static function autoload($class) {
        $prefix = 'KT\\';
        $base_dir = get_template_directory() . '/inc/';

        if (strpos($class, $prefix) !== 0) return;

        $relative = str_replace($prefix, '', $class);
        $file = $base_dir . str_replace('\\', '/', $relative) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
}

// Register immediately
Autoloader::register();