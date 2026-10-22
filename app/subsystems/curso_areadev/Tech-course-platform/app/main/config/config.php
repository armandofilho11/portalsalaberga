<?php

class Config {
    private static $config = null;

    public static function load() {
        $configFile = __DIR__ . '/../.env/config.php';

        if (!file_exists($configFile)) {
            die('Arquivo de configuração não encontrado!');
        }

        self::$config = require($configFile);
    }

    public static function get($key, $default = null) {
        if (self::$config === null) {
            self::load();
        }
        return self::$config[$key] ?? $default;
    }
}
