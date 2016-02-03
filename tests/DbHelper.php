<?php
namespace Test;

abstract class DbHelper
{

    public static function createDi($dbOpts)
    {
        $di = new \Phalcon\DI\FactoryDefault();
        $di->set('db', function () use ($dbOpts) {
            return new \Phalcon\Db\Adapter\Pdo\Mysql($dbOpts);
        });
        return $di;
    }

    public static function populateDb($opts)
    {
        $dbOpts = $opts["db"];
        $dbname = $dbOpts["dbname"];
        self::pdo($dbOpts, null)->query("CREATE DATABASE IF NOT EXISTS $dbname;");

        $pdo = self::pdo($dbOpts, $dbname);

        $schemaPath = $opts["schema"];
        if (substr($schemaPath, 0, 1) != "/") {
            $schemaPath = __DIR__ . "/../$schemaPath";
        }
        $schema = file_get_contents($schemaPath);
        self::pdo($dbOpts, $dbname)->query("use $dbname; " . $schema);
    }

    // Create a direct connection to the MySQL database server
    // To bootstrap the creation of the database, set $dbname to nil
    private static function pdo($dbOpts, $dbname)
    {
        $host = $dbOpts["host"];
        $username = $dbOpts["username"];
        $password = $dbOpts["password"];
        if ($dbname) {
            return new \PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        } else {
            return new \PDO("mysql:host=$host", $username, $password);
        }
    }
}
