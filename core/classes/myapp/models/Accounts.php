<?php

namespace Myapp\Models;

class Accounts extends \Myapp\Models\BaseModels
{

    public $id;
    public $identifier;

    public $firstName;
    public $lastName;
    public $email;

    protected function beforeValidationOnCreate()
    {
        $this->identifier = self::generateUniqueIdentifier(20);
        parent::beforeValidationOnCreate();
    }

    public function toJson($is_admin = false)
    {
        $json = [];
        foreach ($this->columnMap() as $column) {
            $json[$column] = $this->$column;
        }

        return $json;
    }
}
