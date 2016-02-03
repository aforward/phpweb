<?php

require_once __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(function ($fullName) {
    $namespaces = explode('\\', $fullName);
    $className = array_pop($namespaces);

    $path = "./core/classes";
    if ($namespaces) {
        foreach ($namespaces as $ns) {
            $path .= "/". strtolower($ns);
        }
    }
    $path .= "/{$className}.php";
    if (!file_exists($path)) {
        return false;
    }
    require_once $path;
    return true;
});
