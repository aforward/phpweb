<?php

namespace Myapp\Models;

use Phalcon\Mvc\Model\Behavior\Timestampable;
use Rest\Models\Behaviors\Auditable;

abstract class BaseModels extends \Phalcon\Mvc\Model
{

    public $created;
    public $modified;

    protected $defaults = array();

    // Map database column names to class vars
    public function columnMap()
    {
        $reflector = new \ReflectionClass(get_class($this));
        $properties = $reflector->getProperties(\ReflectionProperty::IS_PUBLIC);
        $map = array();
        // Keys are the real names in the table and
        // the values their names in the application
        foreach ($properties as $property) {
            $map[\Util::phpcase($property->getName())] = $property->getName();
        }
        return $map;
    }

    // Handle defaults and timestamps for model
    public function initialize()
    {
        $this->addBehavior(new Timestampable(
            array(
                'beforeValidationOnCreate' => array(
                    'field' => 'created',
                    'format' => \DateTime::ISO8601
                )
            )
        ));

        $this->addBehavior(new Timestampable(
            array(
                'beforeValidationOnUpdate' => array(
                    'field' => 'modified',
                    'format' =>\DateTime::ISO8601
                )
            )
        ));

        // Allow for auditing
        $this->keepSnapshots(true);

        // Log creates and updates
        $this->addBehavior(new Auditable());

    }

    // TODO: This is because hasChanged throws an exception when record is not saved yet
    public function hasChanged($fieldName = null)
    {
        return $this->hasSnapshotData() ? parent::hasChanged($fieldName) : true;
    }

    // TODO: Move this to new NestedEntityModel
    public function save($data = null, $whitelist = null)
    {
        $results = parent::save($data, $whitelist);
        // Update order of child entities
        if (isset($data['childSortOrder'])) {
            $this->updateChildSortOrder($data['childSortOrder']);
        }
        return $results;
    }

    // TODO: Move this to new NestedEntityModel
    protected function updateChildSortOrder($child_sort_orders)
    {
        foreach ($child_sort_orders as $child_entity_id => $new_sort_order) {
            list($entity_type, $entity_id) = explode('_', $child_entity_id);
            $relationship = 'get' . ucfirst($entity_type);
            $this->$relationship(array(
                'id = :id:',
                'bind' => array('id' => $entity_id)
            ))->getFirst()->update(array('sortOrder' => $new_sort_order));
        }
    }

    public function errors()
    {
        $errors = array();
        $messages = $this->getMessages();
        if (is_array($messages)) {
            foreach ($messages as $message) {
                $errors[] = $message->getMessage();
            }
        }
        return implode(', ', $errors);
    }

    // Use default values defined in db
    protected function beforeValidationOnCreate()
    {
        foreach ($this->defaults as $field => $value) {
            if (!isset($this->$field)) {
                $this->$field = $value;
            }
        }
    }

    public function toJson($is_admin = false)
    {
        return $this;
    }

    protected function afterFetch()
    {
        // Convert dates to ISO8601
        $this->toISO8601('created');
        $this->toISO8601('modified');
    }

    protected function toISO8601($column)
    {
        if ($this->$column) {
            $this->$column = date(\DateTime::ISO8601, strtotime($this->$column));
        }
    }

    /**
     * Generate a unique identifier of alpha numeric characters
     * defaulted to 20
     * @param string $desiredLength The desired length of the identifier (default 20)
     * @return A random string of length $desiredLength
     */
    public static function generateUniqueIdentifier($desiredLength = 20)
    {
        switch ($desiredLength) {
            case 20:
                return self::randomHex(10);
            case 40:
                return self::randomHex(13) . date('YmdHis');
            default:
                return substr(self::randomHex(($desiredLength + 1) / 2), 0, $desiredLength);
        }
    }

    private static function randomHex($len)
    {
        return bin2hex(openssl_random_pseudo_bytes($len));
    }
}
