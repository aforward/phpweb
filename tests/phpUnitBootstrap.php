<?php

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
    require_once $path;
});
