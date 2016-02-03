<?php
namespace Myapp\Models\Test;

use Phalcon\Di;
use Myapp\Models\Accounts;
use Test\DbHelper;

class AccountsTest extends \PHPUnit_Framework_TestCase
{

    public static function setUpBeforeClass()
    {
        $opts = [
            "schema" => "sql/myapp/schema.sql",
            "db" => [
              "host" => "127.0.0.1",
              "username" => "root",
              "password" => "",
              "dbname" => "myapp_testing",
            ]
        ];
        DbHelper::populateDb($opts);
        $di = DbHelper::createDi($opts["db"]);
        Di::reset();
        Di::setDefault($di);
    }

    public function testReset()
    {
        $this->assertEquals(0, Accounts::count());
    }

    public function testCreate()
    {
        $account = new Accounts();
        $this->assertEquals(true, $account->save());
        $this->assertEquals(1, Accounts::count());
    }

}
