<?php

namespace App\Model;

class Project
{
    /**
     * @var array
     */
    public $_data; // Should be private. No leading "_" need. It's not Dart
    // This line had invisible trailing spaces. PSR violation
    public function __construct($data) // Need type (array) declaration
    {
        $this->_data = $data; // No data validation
    }

    /**
     * @return int
     */
    public function getId() // Declare :int return type here instead of PHPDoc block
    {
        return (int) $this->_data['id'];
    }

    /**
     * @return string
     */
    public function toJson() // No return type. Better implement JsonSerializable as in Task model
    {
        return json_encode($this->_data);
    }
}
