<?php
namespace MyApp\Test;

use MyApp\Helper;

class HelperTest extends \PHPUnit_Framework_TestCase
{
    public function testMakeSeachable()
    {
        $this->assertEquals("%", Helper::makeSearchable(null));
        $this->assertEquals("%a%", Helper::makeSearchable("a"));
        $this->assertEquals("%abc%", Helper::makeSearchable("abc"));
        $this->assertEquals("%ab c%", Helper::makeSearchable("ab c"));
        $this->assertEquals("ab%c", Helper::makeSearchable("ab%c"));
    }

    public function testParseInt()
    {
        $this->assertEquals(null, Helper::parseInt(null));
        $this->assertEquals(null, Helper::parseInt(""));
        $this->assertEquals(1, Helper::parseInt("1"));
        $this->assertEquals(99, Helper::parseInt("99"));
        $this->assertEquals(null, Helper::parseInt("abc"));
        $this->assertEquals(99, Helper::parseInt(99));
    }
}
