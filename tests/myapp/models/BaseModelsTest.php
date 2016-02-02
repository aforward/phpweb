<?php
namespace Myapp\Models;

use Myapp\Models\BaseModels;

class BaseModelsTest extends \PHPUnit_Framework_TestCase
{

    public function testGenerateUniqueIdentifierDefaultLength()
    {
        $actual = BaseModels::generateUniqueIdentifier();
        $this->assertEquals(20, strlen($actual));
    }

    public function testGenerateUniqueIdentifierSetLength()
    {
        $this->assertEquals(40, strlen(BaseModels::generateUniqueIdentifier(40)));
        $this->assertEquals(18, strlen(BaseModels::generateUniqueIdentifier(18)));
        $this->assertEquals(100, strlen(BaseModels::generateUniqueIdentifier(100)));
        $this->assertEquals(30, strlen(BaseModels::generateUniqueIdentifier(30)));
        $this->assertEquals(12, strlen(BaseModels::generateUniqueIdentifier(12)));
        $this->assertEquals(2, strlen(BaseModels::generateUniqueIdentifier(2)));
        $this->assertEquals(1, strlen(BaseModels::generateUniqueIdentifier(1)));
        $this->assertEquals(10, strlen(BaseModels::generateUniqueIdentifier(10)));
    }

    public function testGenerateUniqueIdentifierAlphaNumeric()
    {
        $actual = BaseModels::generateUniqueIdentifier();
        $same = preg_grep("/^[a-zA-Z0-9]*$/", [$actual]);
        $this->assertEquals($same[0], $actual);
    }

    public function testGenerateUniqueIdentifierSeemsUnique()
    {
        $all = [];
        for ($i = 0; $i < 1000; $i++) {
            $all[] = BaseModels::generateUniqueIdentifier();
        }

        $onlyUnique = array_unique($all);
        $this->assertEquals(count($all), count($onlyUnique));
    }
}
