<?php
spl_autoload_register(function (string $className): void {
    $directories = [
        __DIR__ . '/../controllers',
        __DIR__ . '/../models',
        __DIR__ . '/../middleware',
        __DIR__ . '/',
    ];

    foreach ($directories as $directory) {
        $file = $directory . '/' . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});